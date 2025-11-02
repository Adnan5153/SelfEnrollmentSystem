<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\Enrollment;  // Added for enrollment handling
use App\Models\StudentSubject;
use App\Models\CourseOffering;

class StudentCourseOverviewController extends Controller
{
    /**
     * Display a listing of the student's enrolled courses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // IMPORTANT: Sync credits from all results before checking eligibility
        // This ensures credit_completed is up-to-date with all passed subjects
        // This handles cases where marks were created before auto-sync was implemented
        $student->syncCreditsFromResults();
        
        // Refresh student to get updated credit_completed value
        $student->refresh();

        // Get the student's department directly from the Student model
        if (!$student->department_id) {
            return view('student.layouts.courseoverview', compact('student'))
                ->with('error', 'No department assigned to student');
        }

        // Get subjects for the student's department with marks and prerequisites
        $subjects = Subject::with(['teacher', 'credit', 'department', 'prerequisites', 'course_offerings'])
            ->where('department_id', $student->department_id)
            ->orderBy('year')
            ->orderBy('name')
            ->get();

        // Get all offered subject IDs (subjects that have been offered by admin)
        $offeredSubjectIds = CourseOffering::pluck('subject_id')->unique()->toArray();

        // Get marks for each subject for this student
        $marks = $student->marks()
            ->with('subject')
            ->get()
            ->keyBy('subject_id');

        // Add marks data and eligibility to each subject
        $subjects->each(function ($subject) use ($marks, $student, $offeredSubjectIds) {
            $mark = $marks->get($subject->id);
            $subject->mark = $mark;
            $subject->grade = $mark ? $mark->getGrade() : null;

            // Check if subject is offered by admin
            $subject->is_offered = in_array($subject->id, $offeredSubjectIds) || $subject->course_offerings->isNotEmpty();

            // Determine eligibility status
            $subject->eligibility = $this->determineEligibility($subject, $student);
            
            // Store student marks collection on subject for efficient prerequisite checking in view
            $subject->studentMarks = $marks;
        });

        $eligibleSubjectIds = $subjects->filter(fn($subject) => $subject->eligibility === 'eligible')->pluck('id')->values();

        // Calculate current semester credits for display
        $currentSemesterCredits = $this->calculateCurrentSemesterCredits($student);

        return view('student.layouts.courseoverview', compact('student', 'subjects', 'eligibleSubjectIds', 'currentSemesterCredits'));
    }

    /**
     * Enroll a student in a single course.
     *
     * @param Request $request
     * @param Subject $subject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enrollCourse(Request $request, Subject $subject)
    {
        $student = Auth::guard('student')->user();

        // IMPORTANT: Sync credits from all results before enrollment check
        // This ensures credit_completed is up-to-date
        $student->syncCreditsFromResults();

        // Load prerequisites and credit if not already loaded
        if (!$subject->relationLoaded('prerequisites')) {
            $subject->load('prerequisites');
        }
        if (!$subject->relationLoaded('credit')) {
            $subject->load('credit');
        }

        // Check if course is offered by admin (server-side validation)
        $isOffered = CourseOffering::where('subject_id', $subject->id)->exists();
        if (!$isOffered) {
            return redirect()->back()->with('error', 'This course has not been offered by the admin. Please wait for the course to be offered before enrolling.');
        }

        // Check if already enrolled
        $existingEnrollment = StudentSubject::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->first();
        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // Validate eligibility (re-check server-side for security)
        $eligibility = $this->determineEligibility($subject, $student);
        if ($eligibility !== 'eligible') {
            // Provide detailed error message about missing prerequisites
            $errorMessage = $this->getEligibilityErrorMessage($subject, $student, $eligibility);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Double-check prerequisites before enrollment
        $prerequisiteCheck = $this->validatePrerequisites($subject, $student);
        if (!$prerequisiteCheck['valid']) {
            return redirect()->back()->with('error', $prerequisiteCheck['message']);
        }

        // Check credit limit (maximum 15 credits per semester)
        $currentSemesterCredits = $this->calculateCurrentSemesterCredits($student);
        $courseCredits = $subject->credit ? (float) $subject->credit->credit_hour : 0;
        $totalCreditsAfterEnrollment = $currentSemesterCredits + $courseCredits;
        
        if ($totalCreditsAfterEnrollment > 15) {
            $remainingCredits = 15 - $currentSemesterCredits;
            return redirect()->back()->with('error', 
                'Cannot enroll in ' . $subject->name . '. You have ' . $currentSemesterCredits . ' credits enrolled. ' .
                'Adding this course (' . $courseCredits . ' credits) would exceed the 15 credit limit for this semester. ' .
                ($remainingCredits > 0 ? 'You have ' . $remainingCredits . ' credits remaining.' : 'You have reached the maximum credit limit.')
            );
        }

        // Enroll the student
        StudentSubject::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            // Add other fields like enrollment_date if needed
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in ' . $subject->name . '.');
    }

    /**
     * Calculate current semester credit load for the student
     * Only counts credits from subjects the student is currently enrolled in (pivot table)
     *
     * @param \App\Models\Student $student
     * @return float
     */
    private function calculateCurrentSemesterCredits($student)
    {
        $subjects = $student->subjects()->with('credit')->get();
        $totalCredits = 0;
        foreach ($subjects as $subject) {
            if ($subject->credit) {
                $totalCredits += (float) $subject->credit->credit_hour;
            }
        }
        return $totalCredits;
    }

    /**
     * Determine course eligibility for a student
     * 
     * IMPORTANT: Prerequisites set by admin MUST be met, regardless of year.
     * If prerequisites are not earned, course is ineligible.
     *
     * @param Subject $subject
     * @param Student $student
     * @return string
     */
    private function determineEligibility($subject, $student)
    {
        // FIRST: Always check prerequisites if they exist (admin-set prerequisites must be met)
        // This applies to ALL courses regardless of year
        $prerequisiteCheck = $this->checkPrerequisites($subject, $student);
        if ($prerequisiteCheck === 'not_eligible') {
            return 'not_eligible'; // If prerequisites not met, course is ineligible
        }

        // If it's a 1st year course with no prerequisites, student is eligible
        if ($subject->year === '1st Year' && $subject->prerequisites->isEmpty()) {
            return 'eligible';
        }

        // Check if student has completed enough credits (for courses with credit requirements)
        $minCreditsRequired = 0;
        if ($subject->prerequisites->isNotEmpty()) {
            $minCreditsRequired = $subject->prerequisites->max(function ($prereq) {
                return (int) ($prereq->pivot->required_credits ?? 0);
            });
        }

        // If credit requirement exists, check it
        if ($minCreditsRequired > 0 && $student->credit_completed < $minCreditsRequired) {
            return 'not_eligible';
        }

        // Prerequisites are met (already checked above), so course is eligible
        return 'eligible';
    }

    /**
     * Check if student meets course prerequisites
     * 
     * STRICT RULE: Course is ONLY eligible if ALL prerequisites are EARNED (passed with credits awarded).
     * Prerequisites must be PASSED - meaning student has earned credits for them.
     * If ANY prerequisite is not passed, the course is ineligible.
     *
     * @param Subject $subject
     * @param Student $student
     * @return string 'eligible' or 'not_eligible'
     */
    private function checkPrerequisites($subject, $student)
    {
        // No prerequisites means course is eligible
        if ($subject->prerequisites->isEmpty()) {
            return 'eligible';
        }

        // Get all subjects the student has PASSED (earned credits)
        $passedSubjectIds = $student->getPassedSubjectIds();

        // Check EACH prerequisite - ALL must be PASSED (earned credits)
        foreach ($subject->prerequisites as $prereq) {
            // The prerequisite_id in the pivot table refers to the prerequisite subject
            $prereqSubjectId = $prereq->id;
            
            // Check if student has PASSED this prerequisite (earned credits)
            // This ensures prerequisites are truly completed, not just attempted
            if (!in_array($prereqSubjectId, $passedSubjectIds)) {
                // Even one missing prerequisite makes the course ineligible
                return 'not_eligible';
            }
        }

        // All prerequisites are PASSED (earned credits) - student is eligible
        return 'eligible';
    }

    /**
     * Validate prerequisites before enrollment (more strict check)
     * Ensures prerequisites are PASSED (earned credits), not just attempted
     *
     * @param Subject $subject
     * @param Student $student
     * @return array ['valid' => bool, 'message' => string]
     */
    private function validatePrerequisites($subject, $student)
    {
        if ($subject->prerequisites->isEmpty()) {
            return ['valid' => true, 'message' => ''];
        }

        // Get all subjects the student has PASSED (earned credits)
        $passedSubjectIds = $student->getPassedSubjectIds();
        $missingPrerequisites = [];

        foreach ($subject->prerequisites as $prereq) {
            $prereqSubjectId = $prereq->id;
            
            // Check if student has PASSED this prerequisite (earned credits)
            if (!in_array($prereqSubjectId, $passedSubjectIds)) {
                $missingPrerequisites[] = $prereq->name . ' (' . $prereq->subject_code . ')';
            }
        }

        if (!empty($missingPrerequisites)) {
            $prereqList = implode(', ', $missingPrerequisites);
            return [
                'valid' => false,
                'message' => 'You cannot enroll in ' . $subject->name . ' because you have not passed the following prerequisites: ' . $prereqList . '. Please pass all prerequisite courses (with a grade above F) before enrolling.'
            ];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Get detailed error message based on eligibility status
     *
     * @param Subject $subject
     * @param Student $student
     * @param string $eligibility
     * @return string
     */
    private function getEligibilityErrorMessage($subject, $student, $eligibility)
    {
        if ($eligibility === 'eligible') {
            return '';
        }

        // Check credit requirements
        $minCreditsRequired = 0;
        if ($subject->prerequisites->isNotEmpty()) {
            $minCreditsRequired = $subject->prerequisites->max(function ($prereq) {
                return (int) ($prereq->pivot->required_credits ?? 0);
            });
        }

        if ($minCreditsRequired > 0 && $student->credit_completed < $minCreditsRequired) {
            return 'You cannot enroll in ' . $subject->name . '. You need at least ' . $minCreditsRequired . ' completed credits, but you currently have ' . $student->credit_completed . ' credits.';
        }

        // Check prerequisites - must be PASSED (earned credits)
        if ($subject->prerequisites->isNotEmpty()) {
            $passedSubjectIds = $student->getPassedSubjectIds();
            $missingPrerequisites = [];
            
            foreach ($subject->prerequisites as $prereq) {
                // Check if student has PASSED this prerequisite (earned credits)
                if (!in_array($prereq->id, $passedSubjectIds)) {
                    $missingPrerequisites[] = $prereq->name . ' (' . $prereq->subject_code . ')';
                }
            }

            if (!empty($missingPrerequisites)) {
                $prereqList = implode(', ', $missingPrerequisites);
                return 'You cannot enroll in ' . $subject->name . ' because you have not passed the following prerequisites: ' . $prereqList . '. Please pass all prerequisite courses (with a grade above F) before enrolling.';
            }
        }

        return 'You are not eligible to enroll in ' . $subject->name . '.';
    }
}

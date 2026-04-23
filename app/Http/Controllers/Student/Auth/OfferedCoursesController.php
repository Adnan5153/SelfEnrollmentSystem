<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Mark;
use Illuminate\Support\Facades\Auth;

class OfferedCoursesController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $subjects = Subject::whereHas('offered_in_classes')
            ->with(['prerequisites', 'credit', 'class_routines.teacher'])
            ->get();

        // For offered courses page, current credits should always start at 0
        // This represents credits being selected in the current session only
        // The actual enrolled credits are shown in the "My Enrolled Courses" page
        $currentSemesterCredits = 0;

        // Add credit information and eligibility to each subject
        $subjects->each(function ($subject) use ($student) {
            $subject->credit_hours = $subject->credit ? (float) $subject->credit->credit_hour : 0;
            $subject->is_eligible = $this->checkEligibility($student, $subject);
        });

        // Subjects already enrolled/confirmed by the student
        $enrolledSubjectIds = $student->subjects()->pluck('subjects.id')->toArray();

        return view('student.layouts.offered_courses', compact('subjects', 'currentSemesterCredits', 'enrolledSubjectIds'));
    }

    /**
     * Calculate current semester credit load for the student
     * For the offered courses page, this should always return 0 after enrollment
     * The actual enrolled credits are shown in the "My Enrolled Courses" page
     * This allows students to see pending selections without counting already enrolled courses
     *
     * @param \App\Models\Student $student
     * @return float
     */
    private function calculateCurrentSemesterCredits($student)
    {
        // Always return 0 for the offered courses page
        // The enrolled credits are displayed in the "My Enrolled Courses" page instead
        return 0;
    }

    /**
     * Check if student is eligible to take a subject
     *
     * @param \App\Models\Student $student
     * @param \App\Models\Subject $subject
     * @return bool
     */
    private function checkEligibility($student, $subject)
    {
        // If subject has no prerequisites, student is eligible
        if ($subject->prerequisites->isEmpty()) {
            return true;
        }

        // Get subjects the student has passed (with grades, not F)
        $passedSubjectIds = $this->getPassedSubjectIds($student);

        // Check if student has passed all prerequisites
        foreach ($subject->prerequisites as $prerequisite) {
            if (!in_array($prerequisite->id, $passedSubjectIds)) {
                return false; // Student hasn't passed this prerequisite
            }
        }

        return true; // Student has passed all prerequisites
    }

    /**
     * Get list of subject IDs that the student has passed (grade not F)
     *
     * @param \App\Models\Student $student
     * @return array
     */
    private function getPassedSubjectIds($student)
    {
        // Get all marks for this student
        $marks = Mark::where('student_id', $student->id)
            ->with('subject')
            ->get();

        $passedSubjectIds = [];

        foreach ($marks as $mark) {
            // Get the grade for this mark
            $grade = $mark->getGrade();

            // If grade exists and is not 'F', student passed
            if ($grade && $grade->grade !== 'F' && $grade->grade !== 'f') {
                $passedSubjectIds[] = $mark->subject_id;
            }
        }

        // Return unique subject IDs
        return array_unique($passedSubjectIds);
    }
}

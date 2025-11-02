<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\CourseOffering;
use App\Models\ClassModel;
use App\Models\Mark;

class CourseOverviewController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['department', 'credit', 'prerequisites'])->get();
        $offeredSubjectIds = CourseOffering::pluck('subject_id')->toArray();

        $courses = $subjects->map(function ($subject) use ($offeredSubjectIds) {
            $departmentCode = $subject->department->code ?? 'N/A';

            // Minimum credits required - calculated from prerequisites pivot table
            // This matches the logic in StudentCourseOverviewController
            $minCreditRequired = 0;
            if ($subject->prerequisites->isNotEmpty()) {
                $minCreditRequired = (int) ($subject->prerequisites->max(function ($prereq) {
                    return (int) ($prereq->pivot->required_credits ?? 0);
                }) ?? 0);
            }

            // Pool = same department and same student year (students.year) if subject has a year
            $poolQuery = Student::query()
                ->when($subject->department_id, fn($q) => $q->where('department_id', $subject->department_id))
                ->when(($subject->year ?? null), fn($q) => $q->where('year', $subject->year));

            $poolStudentIds = $poolQuery->pluck('id');

            // Get all students in the pool with their marks loaded for prerequisite checking
            $poolStudents = Student::query()
                ->whereIn('id', $poolStudentIds)
                ->with('marks')
                ->select('id', 'name', 'year', 'credit_completed')
                ->get();

            // Exclude students who have already COMPLETED this subject (passed and earned credits)
            $poolStudentsNotCompleted = $poolStudents->filter(function ($s) use ($subject) {
                return !$s->hasCreditsForSubject($subject->id);
            })->values();

            // Sync credits for all students in pool to ensure credit_completed is accurate
            // This handles cases where credits might not be synced from existing marks
            foreach ($poolStudents as $poolStudent) {
                $poolStudent->syncCreditsFromResults();
            }

            // Filter eligible students based on prerequisites AND credit completion
            // Work with students who haven't already completed the course
            $eligibleStudents = $poolStudentsNotCompleted->filter(function ($student) use ($subject, $minCreditRequired) {
                // FIRST: Always check prerequisites if they exist (matches student enrollment logic)
                // This applies to ALL courses regardless of year
                // Prerequisites must be PASSED (earned credits) - not just attempted
                if ($subject->prerequisites->isNotEmpty()) {
                    $passedSubjectIds = $student->getPassedSubjectIds();

                    foreach ($subject->prerequisites as $prereq) {
                        // Check if student has PASSED this prerequisite (earned credits)
                        if (!in_array($prereq->id, $passedSubjectIds)) {
                            return false; // Prerequisite not passed - not eligible
                        }
                    }
                }

                // If it's a 1st year course with no prerequisites, student is eligible (no credit check needed)
                if ($subject->year === '1st Year' && $subject->prerequisites->isEmpty()) {
                    return true;
                }

                // Check credit requirement (for courses with credit requirements set in prerequisites)
                // Credit requirement checks total earned credits (credit_completed field)
                if ($minCreditRequired > 0) {
                    // Use actual earned credits (should match credit_completed but recalculate if needed)
                    $earnedCredits = $student->credit_completed ?? 0;

                    if ($earnedCredits < $minCreditRequired) {
                        return false; // Credit requirement not met
                    }
                }

                // All checks passed - student is eligible
                return true;
            });

            $eligibleNow = $eligibleStudents->count();
            $eligibleStudentsArray = $eligibleStudents->values()->toArray();

            // Count students who have met prerequisites (for prereq_met count)
            // Consider only students who have NOT already completed the course
            // Prerequisites are considered "met" when student has PASSED (earned credits) for all prerequisites
            $prereqMetCount = 0;
            if ($subject->prerequisites->isNotEmpty()) {
                $prereqMetCount = $poolStudentsNotCompleted->filter(function ($student) use ($subject) {
                    $passedSubjectIds = $student->getPassedSubjectIds();

                    foreach ($subject->prerequisites as $prereq) {
                        // Student must have PASSED this prerequisite
                        if (!in_array($prereq->id, $passedSubjectIds)) {
                            return false;
                        }
                    }
                    return true;
                })->count();
            } else {
                // If no prerequisites, all students in (not-completed) pool have met prerequisites
                $prereqMetCount = $poolStudentsNotCompleted->count();
            }

            // Calculate row ID
            $rowId = $subject->id;

            return [
                'id'             => $subject->id,
                'name'           => $subject->name,
                'code'           => $subject->subject_code,
                'year'           => $subject->year ?? 'N/A',
                'department'     => $departmentCode,
                'type'           => ucfirst($subject->credit->subject_type ?? 'Theory'),
                'credit_hour'    => (float) ($subject->credit->credit_hour ?? 0),
                'total'          => $poolStudentsNotCompleted->count(),           // per-course pool size (exclude completed)
                'pool_ids'       => $poolStudentsNotCompleted->pluck('id')->values()->all(),   // used by view to de-dup across rows
                'eligible'       => $eligibleNow,
                'prereq_met'     => $prereqMetCount,                    // students who have met prerequisites
                'min_credit_req' => $minCreditRequired,
                'is_offered'     => in_array($subject->id, $offeredSubjectIds, true),
                'credit_eligible_students' => $eligibleStudentsArray,   // actual student data (fully eligible)
                'fully_eligible_students' => $eligibleStudentsArray,    // same as credit_eligible_students
                'rowId'          => $rowId,
            ];
        })->values()->all();

        // Build filter sources
        $yearOrder = ['1st Year', '2nd Year', '3rd Year', '4th Year', 'Technical Electives'];
        $years = array_values(array_unique(array_map(fn($course) => $course['year'] ?? 'N/A', $courses)));
        usort($years, fn($a, $b) => array_search($a, $yearOrder) <=> array_search($b, $yearOrder));
        $departments = array_values(array_unique(array_map(fn($course) => $course['department'] ?? 'N/A', $courses)));
        $types = array_values(array_unique(array_map(fn($course) => $course['type'] ?? 'N/A', $courses)));

        // Summary calculations
        $totalCoursesCount = count($courses);
        $initialUniquePoolIds = collect($courses)->flatMap(fn($course) => $course['pool_ids'] ?? [])->unique();
        $hasPoolIds = $initialUniquePoolIds->isNotEmpty();
        $totalStudentsCount = $hasPoolIds
            ? $initialUniquePoolIds->count()
            : array_sum(array_map(fn($course) => (int) ($course['total'] ?? 0), $courses));
        $totalEligibleCount = array_sum(array_map(fn($course) => (int) ($course['eligible'] ?? 0), $courses));
        $totalPrereqMetCount = array_sum(array_map(fn($course) => (int) ($course['prereq_met'] ?? 0), $courses));

        // Calculate eligible and prerequisite percentages for each course
        $coursesWithPercentages = array_map(function ($course) {
            $eligiblePercent = ($course['total'] ?? 0) > 0
                ? round((($course['eligible'] ?? 0) / $course['total']) * 100)
                : 0;
            $prereqPercent = ($course['total'] ?? 0) > 0
                ? round((($course['prereq_met'] ?? 0) / $course['total']) * 100)
                : 0;

            return array_merge($course, [
                'eligible_percent' => $eligiblePercent,
                'prereq_percent' => $prereqPercent,
            ]);
        }, $courses);

        $classes = ClassModel::orderBy('class_name')->get();

        // Calculate total credits being offered
        $offeredCourses = collect($coursesWithPercentages)->filter(fn($course) => $course['is_offered'] ?? false);
        $totalCreditsOffered = $offeredCourses->sum(fn($course) => (float) ($course['credit_hour'] ?? 0));
        $totalOfferedCourses = $offeredCourses->count();

        return view('admin.layouts.courseoverview', compact(
            'coursesWithPercentages',
            'classes',
            'totalCoursesCount',
            'totalStudentsCount',
            'totalEligibleCount',
            'totalPrereqMetCount',
            'totalCreditsOffered',
            'totalOfferedCourses',
            'years',
            'departments',
            'types'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'enforce_prereq' => ['nullable', 'boolean'],
            'credit_hour' => ['nullable', 'string'],
        ]);

        $courseOffering = CourseOffering::updateOrCreate(
            [
                'class_id' => $validated['class_id'] ?? null,
                'subject_id' => $validated['subject_id'],
            ],
            [
                'enforce_prereq' => (bool)($validated['enforce_prereq'] ?? true),
                'credit_hour' => $validated['credit_hour'] ?? null,
            ]
        );

        return back()->with([
            'success' => 'Course offering saved.',
            'saved_subject_id' => (int) $validated['subject_id'],
        ]);
    }

    public function edit($id)
    {
        return response('Course Overview edit - Not implemented', 501);
    }

    public function update(Request $request, $id)
    {
        return response('Course Overview update - Not implemented', 501);
    }

    public function destroy($id)
    {
        return response('Course Overview destroy - Not implemented', 501);
    }

    /**
     * Drop a course offering (remove from all classes and course offerings)
     */
    public function drop($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);

        // Get all course offerings for this subject
        $offerings = CourseOffering::where('subject_id', $subjectId)->get();

        // Remove from class_subject pivot table for all classes
        foreach ($offerings as $offering) {
            if ($offering->class_id) {
                $class = ClassModel::find($offering->class_id);
                if ($class) {
                    $class->offered_subjects()->detach($subjectId);
                }
            }
        }

        // Delete all course offerings for this subject
        CourseOffering::where('subject_id', $subjectId)->delete();

        return redirect()->route('courseoverview.index')
            ->with('success', 'Course offering dropped successfully. Removed from all classes.');
    }
}

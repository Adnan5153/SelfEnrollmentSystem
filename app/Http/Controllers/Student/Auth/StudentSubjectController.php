<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    /**
     * Show list of offered and enrolled subjects for this student.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Offered subjects (for student's class)
        $offeredSubjects = $student->class
            ? $student->class->offered_subjects()->with(['credit', 'prerequisites', 'class_routines.teacher'])->get()
            : collect();

        // Already enrolled subjects
        $enrolledSubjects = method_exists($student, 'subjects')
            ? $student->subjects()->with(['credit', 'prerequisites', 'class_routines.teacher'])->get()
            : collect();

        return view('student.layouts.subjects', [
            'offeredSubjects' => $offeredSubjects,
            'enrolledSubjects' => $enrolledSubjects,
        ]);
    }

    /**
     * Bulk enrollment: Enroll student in multiple courses at once.
     */
    public function enrollBulk(Request $request)
    {
        $student = Auth::guard('student')->user();
        $subjectIds = $request->input('selected_courses', []);

        if (empty($subjectIds)) {
            return redirect()->back()->with('error', 'No courses selected.');
        }

        // Exclude courses already enrolled
        $alreadyEnrolled = $student->subjects()->pluck('subjects.id')->toArray();
        $toEnroll = array_diff($subjectIds, $alreadyEnrolled);

        if (!empty($toEnroll)) {
            $student->subjects()->attach($toEnroll);
            return redirect()->back()->with('success', 'Courses enrolled successfully!');
        }

        return redirect()->back()->with('info', 'You are already enrolled in the selected courses.');
    }

    /**
     * Drop a course from the student's enrolled subjects.
     */

    public function drop($subjectId)
    {
        $student = Auth::guard('student')->user();

        // Detach only if the student is enrolled
        if ($student->subjects()->where('subjects.id', $subjectId)->exists()) {
            $student->subjects()->detach($subjectId);
        }

        return redirect()->back()->with('success', 'Course dropped successfully!');
    }
}

<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassRoutine;

class StudentClassRoutineController extends Controller
{
    /**
     * Display the student's class routine for enrolled courses only.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // If the relationship is not set up, guard against null
        if (!$student || !method_exists($student, 'subjects')) {
            return view('student.layouts.classroutine', ['class_routines' => collect()]);
        }

        // Get only subjects the student has enrolled in
        $enrolledSubjectIds = $student->subjects()->pluck('subjects.id');

        $class_routines = ClassRoutine::whereIn('subject_id', $enrolledSubjectIds)
            ->with(['subject', 'teacher'])
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();

        return view('student.layouts.classroutine', compact('class_routines'));
    }
}

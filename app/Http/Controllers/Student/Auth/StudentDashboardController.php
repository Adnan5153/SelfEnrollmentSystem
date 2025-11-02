<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClassRoutine;
use App\Models\ExamSchedule;
use App\Models\Teacher;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Get IDs of subjects the student has ENROLLED in
        $subjectIds = $student->subjects()->pluck('subjects.id');

        // Total ENROLLED Subjects
        $subjectsCount = $subjectIds->count();

        // Total Credits from ENROLLED Subjects
        $totalCredits = Subject::with('credit')
            ->whereIn('id', $subjectIds)
            ->get()
            ->sum(function ($subject) {
                return $subject->credit ? $subject->credit->credit_hour : 0;
            });

        // Total Teachers teaching the ENROLLED subjects
        $teachersCount = Teacher::whereIn(
            'id',
            Subject::whereIn('id', $subjectIds)->pluck('teacher_id')
        )->distinct('id')->count('id');

        // Today's Classes (for ENROLLED subjects only)
        $today = Carbon::now()->format('l'); // Full weekday name, e.g. 'Monday'
        $todayClasses = ClassRoutine::with(['subject', 'teacher'])
            ->whereIn('subject_id', $subjectIds)
            ->where('day_of_week', $today)
            ->get();

        // Exam Schedules for ENROLLED subjects only
        $examSchedules = ExamSchedule::with('subject')
            ->whereIn('subject_id', $subjectIds)
            ->orderBy('exam_date', 'asc')
            ->get();

        return view('student.dashboard', compact(
            'subjectsCount',
            'totalCredits', // pass totalCredits to the view
            'teachersCount',
            'todayClasses',
            'examSchedules'
        ));
    }
}

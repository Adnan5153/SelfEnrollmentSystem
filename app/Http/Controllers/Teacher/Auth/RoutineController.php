<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClassRoutine;
use Illuminate\Support\Facades\Auth;

class RoutineController extends Controller
{
    public function index()
    {
        $teacherId = Auth::id();

        // Only eager load subject and teacher
        $routines = ClassRoutine::where('teacher_id', $teacherId)
            ->with(['subject'])
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();

        $groupedByDay = $routines->groupBy('day_of_week');

        return view('teacher.layouts.routine', compact('groupedByDay'));
    }
}

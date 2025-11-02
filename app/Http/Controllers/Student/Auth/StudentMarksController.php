<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class StudentMarksController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $marks = Mark::with(['teacher', 'student', 'subject'])
            ->where('student_id', $student->id)
            ->get();

        $grades = Grade::all();

        return view('student.layouts.marks', compact('marks', 'grades'));
    }
}

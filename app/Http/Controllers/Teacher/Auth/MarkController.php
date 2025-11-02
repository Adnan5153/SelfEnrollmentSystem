<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Mark;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MarkController extends Controller
{
    public function create()
    {
        $teacher = Auth::user();
        $subjects = $teacher->subjects()->get();
        return view('teacher.layouts.addmarks', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|integer|min:0|max:100',
        ]);

        $subject = Subject::findOrFail($validatedData['subject_id']);
        $student = Student::findOrFail($validatedData['student_id']);

        // Create the mark
        $mark = Mark::create([
            'teacher_id' => Auth::id(),
            'student_id' => $validatedData['student_id'], // FK to students.id
            'subject_id' => $validatedData['subject_id'], // FK to subjects.id
            'marks' => $validatedData['marks'],
        ]);

        // CREDIT EARNING RULES:
        // Credits are calculated from ALL passed subjects (including prerequisites)
        // When a result is given, recalculate total credits from all passed subjects
        // This ensures credit_completed matches actual passed prerequisites
        
        // Sync credits from all results (recalculates from all passed subjects including prerequisites)
        $oldCredits = $student->credit_completed;
        $student->syncCreditsFromResults();
        $newCredits = $student->credit_completed;
        
        if ($mark->isPassingGrade() && $subject->credit) {
            $credits = (float) $subject->credit->credit_hour;
            Log::info("Credit sync for student {$student->name} (ID: {$student->id}): Result given for {$subject->name}. Credits recalculated from all passed subjects: {$oldCredits} -> {$newCredits}");
        } else {
            Log::info("Credit sync for student {$student->name} (ID: {$student->id}): Result given for {$subject->name} (failed). Credits recalculated from all passed subjects: {$oldCredits} -> {$newCredits}");
        }

        return redirect()->route('teacher.addmarks')->with('success', 'Marks have been successfully added.');
    }

    public function fetchStudents(Request $request)
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section' => 'required|string|max:10'
        ]);

        $students = Student::where('class_id', $request->class_id)
            ->where('section', $request->section)
            ->get(['id', 'name']);

        return response()->json($students);
    }

    public function fetchSubjects(Request $request)
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
        ]);

        $subjects = Subject::where('class_id', $request->class_id)->get(['id', 'name']);

        return response()->json($subjects);
    }

    // New AJAX endpoint: fetch students by subject
    public function fetchStudentsBySubject(Request $request)
    {
        try {
            $request->validate([
                'subject_id' => 'required|integer|exists:subjects,id',
            ]);

            $subject = Subject::findOrFail($request->subject_id);

            // Use fully qualified column names to avoid ambiguous column error
            $students = $subject->students()->get(['students.id', 'students.name']);

            return response()->json($students);
        } catch (\Exception $e) {
            Log::error('Error in fetchStudentsBySubject: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

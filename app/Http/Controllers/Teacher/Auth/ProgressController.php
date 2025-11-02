<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgressController extends Controller
{
    public function index()
    {
        $marks = Mark::with(['student', 'subject'])->get();
        $grades = Grade::all();
        return view('teacher.layouts.progress', compact('marks', 'grades'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marks' => 'required|integer|min:0|max:100',
            'remarks' => 'nullable|string|max:255'
        ]);

        $mark = Mark::with(['student', 'subject.credit'])->findOrFail($id);
        $oldMarks = $mark->marks;

        $mark->update([
            'marks' => $request->marks,
            'remarks' => $request->remarks,
        ]);

        // CREDIT EARNING RULES:
        // Credits are calculated from ALL passed subjects (including prerequisites)
        // When a result is updated, recalculate total credits from all passed subjects
        // This ensures credit_completed matches actual passed prerequisites
        
        // Sync credits from all results (recalculates from all passed subjects including prerequisites)
        $oldCredits = $mark->student->credit_completed;
        $mark->student->syncCreditsFromResults();
        $newCredits = $mark->student->credit_completed;
        
        if ($mark->isPassingGrade() && $mark->subject->credit) {
            $credits = (float) $mark->subject->credit->credit_hour;
            Log::info("Credit sync for student {$mark->student->name} (ID: {$mark->student->id}): Result updated for {$mark->subject->name}. Credits recalculated from all passed subjects: {$oldCredits} -> {$newCredits}");
        } else {
            Log::info("Credit sync for student {$mark->student->name} (ID: {$mark->student->id}): Result updated for {$mark->subject->name} (failed). Credits recalculated from all passed subjects: {$oldCredits} -> {$newCredits}");
        }

        return redirect()->route('teacher.progress')->with('success', 'Marks updated successfully.');
    }

    public function destroy($id)
    {
        $mark = Mark::with('student')->findOrFail($id);
        $student = $mark->student;
        
        // Store old credits before deletion
        $oldCredits = $student->credit_completed;
        
        $mark->delete();

        // After deleting a mark, recalculate credits from all remaining passed subjects
        $student->syncCreditsFromResults();
        $newCredits = $student->credit_completed;
        
        Log::info("Credit sync for student {$student->name} (ID: {$student->id}): Mark deleted. Credits recalculated from all passed subjects: {$oldCredits} -> {$newCredits}");

        return redirect()->route('teacher.progress')->with('success', 'Marks deleted successfully.');
    }
}

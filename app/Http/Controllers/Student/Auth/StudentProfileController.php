<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AllStudent;
use App\Models\TotalCredit;
use App\Models\Mark;
use App\Models\Grade;
use App\Models\ParentModel;

class StudentProfileController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $profile = [];
        $creditEarned = 0;
        $creditRemaining = 0;
        $completedSubjects = 0;
        $allStudent = null;
        $parent = null;

        if ($student) {
            // Link Student to AllStudent by email to enrich available fields
            $as = AllStudent::with(['parent', 'department', 'class'])
                ->where('email', $student->email)
                ->first();

            $allStudent = $as;

            if ($as) {
                $profile['gender'] = $as->gender ?? null;
                $profile['dob'] = $as->date_of_birth ?? null;
                $profile['religion'] = $as->religion ?? null;
                $profile['program'] = optional($as->department)->name ?? null;
                if ($as->parent) {
                    $parent = $as->parent;
                    $profile['father_name'] = $as->parent->father_name ?? null;
                    $profile['mother_name'] = $as->parent->mother_name ?? null;
                    $profile['parent_occupation'] = trim(implode(' / ', array_filter([
                        $as->parent->father_occupation ?? null,
                        $as->parent->mother_occupation ?? null,
                    ]))) ?: null;
                    $profile['parent_email'] = $as->parent->parent_email ?? null;
                    $profile['phone'] = $as->parent->phone_number ?? null;
                    $profile['present_address'] = $as->parent->present_address ?? null;
                    $profile['permanent_address'] = $as->parent->permanent_address ?? null;
                }
            }

            // Credits: earned from student's credit_completed; total from TotalCredit table (fallback 160)
            $totalCreditRow = TotalCredit::query()->orderBy('id', 'desc')->first();
            $totalRequired = $totalCreditRow ? (float) $totalCreditRow->total_credit : 160.0;
            $creditEarned = (float) ($student->credit_completed ?? 0);
            $creditRemaining = max($totalRequired - $creditEarned, 0);

            // Completed subjects: count marks where mapped grade != 'F'
            $marks = Mark::with('subject')->where('student_id', $student->id)->get();
            if ($marks->isNotEmpty()) {
                // Preload grades to avoid repeated queries
                $grades = Grade::all();
                $completedSubjects = $marks->filter(function ($m) use ($grades) {
                    $g = $grades->first(function ($gr) use ($m) {
                        return $m->marks >= $gr->min_marks && $m->marks <= $gr->max_marks;
                    });
                    return $g && strtoupper($g->grade) !== 'F';
                })->count();
            }
        }

        return view('student.layouts.profile', [
            'student' => $student,
            'allStudent' => $allStudent,
            'parent' => $parent,
            'profile' => $profile,
            'creditEarned' => $creditEarned,
            'creditRemaining' => $creditRemaining,
            'completedSubjects' => $completedSubjects,
        ]);
    }

    /**
     * Update parent information
     */
    public function updateParent(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return redirect()->route('student.profile.index')->with('error', 'Student not found.');
        }

        $allStudent = AllStudent::where('email', $student->email)->first();
        
        if (!$allStudent || !$allStudent->parent) {
            return redirect()->route('student.profile.index')->with('error', 'Parent information not found.');
        }

        $parent = $allStudent->parent;

        // Validate parent data
        $validatedData = $request->validate([
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'parent_email' => 'required|email|max:255|unique:allparents,parent_email,' . $parent->id . ',id',
        ]);

        // Update parent details
        $parent->update($validatedData);

        return redirect()->route('student.profile.index')->with('success', 'Parent information updated successfully.');
    }

    /**
     * Update contact information
     */
    public function updateContact(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return redirect()->route('student.profile.index')->with('error', 'Student not found.');
        }

        $allStudent = AllStudent::where('email', $student->email)->first();
        
        if (!$allStudent || !$allStudent->parent) {
            return redirect()->route('student.profile.index')->with('error', 'Contact information not found.');
        }

        $parent = $allStudent->parent;

        // Validate contact data
        $validatedData = $request->validate([
            'phone_number' => 'required|string|max:20',
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
        ]);

        // Update contact details
        $parent->update($validatedData);

        return redirect()->route('student.profile.index')->with('success', 'Contact information updated successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    /**
     * Display the grades listing.
     */
    public function index()
    {
        $grades = Grade::all();
        return view('admin.layouts.allgrades', compact('grades'));
    }

    /**
     * Show form to add a new grade.
     */
    public function create()
    {
        return view('admin.layouts.addgrades');
    }

    /**
     * Store a new grade.
     */
    public function store(Request $request)
    {
        $request->validate([
            'min_marks' => 'required|integer|min:0|max:100',
            'max_marks' => 'required|integer|min:0|max:100|gt:min_marks',
            'grade' => 'required|string|max:2|unique:grades,grade',
            'remarks' => 'nullable|string|max:255',
        ]);

        Grade::create([
            'min_marks' => $request->min_marks,
            'max_marks' => $request->max_marks,
            'grade' => $request->grade,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('grades.create')->with('success', 'Grade added successfully.');
    }

    /**
     * Show the edit form for a grade.
     */
    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        return view('admin.layouts.modal.editgrade', compact('grade'));
    }

    /**
     * Update an existing grade.
     */
    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);

        $request->validate([
            'min_marks' => 'required|integer|min:0|max:100',
            'max_marks' => 'required|integer|min:0|max:100|gt:min_marks',
            'grade' => 'required|string|max:2|unique:grades,grade,' . $id,
            'remarks' => 'nullable|string|max:255',
        ]);

        $grade->update([
            'min_marks' => $request->min_marks,
            'max_marks' => $request->max_marks,
            'grade' => $request->grade,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('grades.index')->with('success', 'Grade updated successfully.');
    }

    /**
     * Delete a grade.
     */
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully.');
    }
}

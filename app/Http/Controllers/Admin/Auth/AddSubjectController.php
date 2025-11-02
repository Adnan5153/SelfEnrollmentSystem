<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Department;

class AddSubjectController extends Controller
{
    /**
     * Display the subject management page.
     */
    public function index()
    {
        $departments = Department::all();
        $teachers = Teacher::select('id', 'name', 'email')->get();
        $subjects = Subject::with(['teacher', 'credit'])->get();
        $credits = Credit::all();

        return view('admin.layouts.addsubject', compact('subjects', 'credits', 'teachers', 'departments'));
    }

    /**
     * Store a new subject.
     */
    public function store(Request $request)
    {
        Log::info('Subject Form Data:', $request->all());

        $subject = Subject::create([
            'name' => $request->name,
            'subject_code' => $request->subject_code,
            'year' => $request->year,
            'credit_id' => $request->credit_id,
            'teacher_id' => $request->teacher_id,
            'department_id' => $request->department_id,
        ]);

        Log::info('New Subject Created:', $subject->toArray());

        return redirect()->route('subjects.index')->with('success', 'Subject and teacher assigned successfully.');
    }

    /**
     * Show the edit form for a subject.
     */
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $teachers = Teacher::select('id', 'name', 'email')->get();
        $credits = Credit::all();

        return view('admin.layouts.edit_subject', compact('subject', 'teachers', 'credits'));
    }

    /**
     * Update an existing subject.
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $subject->update([
            'name' => $request->name,
            'subject_code' => $request->subject_code,
            'year' => $request->year,
            'teacher_id' => $request->teacher_id,
            'credit_id' => $request->credit_id,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    /**
     * Delete a subject.
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }

    /**
     * AJAX Filter: Fetch subjects based on multiple criteria.
     */
    public function filterSubjects(Request $request)
    {
        $searchTerm = $request->query('search');
        $yearValue = $request->query('year');
        $creditId = $request->query('credit_id');
        $departmentId = $request->query('department_id');

        $subjects = Subject::query();

        // Search by subject name
        if ($searchTerm) {
            $subjects->where('name', 'like', '%' . $searchTerm . '%');
        }

        // Filter by year
        if ($yearValue) {
            $subjects->where('year', $yearValue);
        }

        // Filter by credit type
        if ($creditId) {
            $subjects->where('credit_id', $creditId);
        }

        // Filter by department
        if ($departmentId) {
            $subjects->where('department_id', $departmentId);
        }

        $subjects = $subjects->with(['teacher', 'credit', 'department'])->get();

        return response()->json($subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'subject_code' => $subject->subject_code,
                'year' => $subject->year ?? 'N/A',
                'department_name' => $subject->department->name ?? 'N/A',
                'subject_type' => $subject->credit->subject_type ?? 'N/A',
                'credit_hour' => $subject->credit->credit_hour ?? 'N/A',
                'teacher_name' => $subject->teacher->name ?? 'No Teacher Assigned',
            ];
        }));
    }
}

<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Credit;

class CreditDistributionController extends Controller
{
    /**
     * Display the credit distribution page.
     */
    public function index()
    {
        $credits = Credit::paginate(10);  // ✅ No eager loading
        $subjects = []; // or remove if not needed
        return view('admin.layouts.creditdistribution', compact('credits', 'subjects'));
    }


    /**
     * Store a new credit distribution.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_type' => 'required|in:theory,lab',
            'credit_hour' => 'required|numeric|min:0.5|max:10',
        ]);

        Credit::create([
            'subject_type' => $request->subject_type,
            'credit_hour' => $request->credit_hour,
        ]);

        return redirect()->route('creditdistribution.index')->with('success', 'Credit distribution added successfully.');
    }

    /**
     * Update an existing credit distribution.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_type' => 'required|in:theory,lab',
            'credit_hour' => 'required|numeric|min:0.5|max:10',
        ]);

        $credit = Credit::findOrFail($id);
        $credit->update([
            'subject_type' => $request->subject_type,
            'credit_hour' => $request->credit_hour,
        ]);

        return redirect()->route('creditdistribution.index')->with('success', 'Credit distribution updated successfully.');
    }


    /**
     * Delete a credit distribution.
     */
    public function destroy($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->delete();

        return redirect()->route('creditdistribution.index')->with('success', 'Credit distribution deleted successfully.');
    }

    /**
     * AJAX Filter: Fetch subjects based on selected class-section.
     */
    public function filterSubjects(Request $request)
    {
        $classId = $request->input('class_id');
        $subjects = Subject::where('class_id', $classId)->get();

        return response()->json($subjects);
    }
}

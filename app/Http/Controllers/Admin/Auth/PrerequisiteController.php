<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class PrerequisiteController extends Controller
{
    /**
     * Display the prerequisite management form.
     */
    public function index()
    {
        // Eager load prerequisites to avoid N+1 queries
        $subjects = Subject::with('prerequisites')->get();

        return view('admin.layouts.prerequisite', compact('subjects'));
    }

    /**
     * Store or update the prerequisites for a given subject.
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'prerequisite_ids' => 'required|array|min:1',
            'prerequisite_ids.*' => 'required|exists:subjects,id|different:subject_id',
            'required_credits' => 'nullable|integer|min:0|max:999',
        ], [
            'prerequisite_ids.min' => 'You must select at least one prerequisite subject.',
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        $credit = $request->required_credits ?? 0;

        // Prepare pivot data with uniform credit per prerequisite
        $prerequisites = [];
        foreach ($request->prerequisite_ids as $prerequisiteId) {
            $prerequisites[$prerequisiteId] = [
                'required_credits' => $credit
            ];
        }

        // Sync prerequisites with pivot table
        $subject->prerequisites()->sync($prerequisites);

        return redirect()
            ->route('prerequisite.index')
            ->with('success', 'Prerequisites updated successfully.');
    }

    /**
     * Remove all prerequisites for a specific subject.
     */
    public function destroy($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $subject->prerequisites()->detach();

        return redirect()
            ->route('prerequisite.index')
            ->with('success', 'All prerequisites removed for the subject.');
    }
}

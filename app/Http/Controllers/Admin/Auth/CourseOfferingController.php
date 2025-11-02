<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseOfferingController extends Controller
{
    /**
     * Display the form to assign subjects to a class.
     */
    public function index()
    {
        // Sort classes by class_name (optional, improves dropdown clarity)
        $classes = ClassModel::with(['offered_subjects.credit', 'offered_subjects.department'])->orderBy('class_name')->get();

        // Sort subjects alphabetically by name
        $subjects = Subject::orderBy('name')->get();

        // Get all course offerings with subject and class details for table display
        $offerings = CourseOffering::with(['subject.credit', 'subject.department', 'class'])
            ->orderBy('class_id')
            ->orderBy('subject_id')
            ->get();

        return view('admin.layouts.courseoffering', compact('classes', 'subjects', 'offerings'));
    }

    /**
     * Store or update the subjects offered to a class.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        // Debug: Log the incoming data
        Log::info('Subject IDs received:', $request->subject_ids ?? []);

        $subjectIds = $request->subject_ids ?? [];
        $class->offered_subjects()->sync($subjectIds);

        // Also create/update CourseOffering records for the table display
        // Delete existing offerings for this class first
        CourseOffering::where('class_id', $class->id)->delete();

        // Create new CourseOffering records for each subject
        foreach ($subjectIds as $subjectId) {
            CourseOffering::create([
                'class_id' => $class->id,
                'subject_id' => $subjectId,
                'enforce_prereq' => true, // Default to true
            ]);
        }

        return redirect()->route('courseoffering.index')->with('success', 'Subjects successfully offered to the class.');
    }

    /**
     * Offer a specific course to a class from Course Overview
     */
    public function offerToClass(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'enforce_prereq' => 'nullable|boolean',
        ]);

        $class = ClassModel::findOrFail($validated['class_id']);

        // Attach the subject to the class if not already attached
        if (!$class->offered_subjects()->where('subjects.id', $validated['subject_id'])->exists()) {
            $class->offered_subjects()->attach($validated['subject_id']);
        }

        // Create or update course offering
        CourseOffering::updateOrCreate(
            [
                'class_id' => $validated['class_id'],
                'subject_id' => $validated['subject_id'],
            ],
            [
                'enforce_prereq' => (bool)($validated['enforce_prereq'] ?? true),
            ]
        );

        return redirect()->route('courseoverview.index')->with('success', 'Course successfully offered to class.');
    }
    /**
     * Show the form to edit subjects offered to a class.
     */
    public function edit($id)
    {
        $class = ClassModel::findOrFail($id);
        $classes = ClassModel::orderBy('class_name')->get();
        $subjects = Subject::orderBy('name')->get();
        $offered_subjects = $class->offered_subjects()->pluck('subjects.id')->toArray();

        return view('admin.layouts.courseoffering', compact('class', 'classes', 'subjects', 'offered_subjects'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        $class = ClassModel::findOrFail($id);

        // Debug: Log the incoming data
        Log::info('Subject IDs received for update:', $request->subject_ids ?? []);

        $subjectIds = $request->subject_ids ?? [];
        $class->offered_subjects()->sync($subjectIds);

        // Also create/update CourseOffering records for the table display
        // Delete existing offerings for this class first
        CourseOffering::where('class_id', $class->id)->delete();

        // Create new CourseOffering records for each subject
        foreach ($subjectIds as $subjectId) {
            CourseOffering::create([
                'class_id' => $class->id,
                'subject_id' => $subjectId,
                'enforce_prereq' => true, // Default to true
            ]);
        }

        return redirect()->route('courseoffering.index')->with('success', 'Subjects successfully updated for the class.');
    }

    /**
     * Get the subjects offered to a class.
     */
    public function getSubjects($id)
    {
        $class = ClassModel::findOrFail($id);
        return response()->json($class->offered_subjects()->pluck('subjects.id'));
    }


    /**
     * Remove all subjects offered to a class.
     */
    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        $class->offered_subjects()->detach();

        // Also delete CourseOffering records for this class
        CourseOffering::where('class_id', $class->id)->delete();

        return redirect()->route('courseoffering.index')->with('success', 'All subjects removed from the class.');
    }

    /**
     * List all selected (offered) subjects across classes.
     */
    public function selected()
    {
        $offerings = CourseOffering::with(['class', 'subject.credit'])->orderByDesc('id')->get();
        $subjects = Subject::orderBy('name')->get();
        
        // Calculate total credits across all offerings
        $totalCredits = $offerings->sum(function ($offering) {
            return (float) ($offering->credit_hour ?? $offering->subject->credit->credit_hour ?? 0);
        });
        
        // Calculate credits per class
        $creditsByClass = $offerings->groupBy('class_id')->map(function ($classOfferings) {
            return $classOfferings->sum(function ($offering) {
                return (float) ($offering->credit_hour ?? $offering->subject->credit->credit_hour ?? 0);
            });
        });
        
        return view('admin.layouts.selected_offerings', compact('offerings', 'subjects', 'totalCredits', 'creditsByClass'));
    }

    /**
     * Update a specific course offering (prereq flag, credit hour).
     */
    public function updateOffering(Request $request, $id)
    {
        $validated = $request->validate([
            'enforce_prereq' => ['nullable', 'boolean'],
            'credit_hour' => ['nullable', 'string'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        $offering = CourseOffering::findOrFail($id);
        $offering->enforce_prereq = (bool)($validated['enforce_prereq'] ?? false);
        $offering->credit_hour = $validated['credit_hour'] ?? null;
        $offering->subject_id = $validated['subject_id'];
        $offering->save();

        return redirect()->route('courseoffering.selected')->with('success', 'Offering updated.');
    }

    /**
     * Delete a specific course offering.
     */
    public function deleteOffering($id)
    {
        $offering = CourseOffering::findOrFail($id);
        
        // Remove from class_subject pivot table
        if ($offering->class_id && $offering->subject_id) {
            $class = ClassModel::find($offering->class_id);
            if ($class) {
                $class->offered_subjects()->detach($offering->subject_id);
            }
        }
        
        // Delete the course offering
        $offering->delete();

        return redirect()->route('courseoffering.selected')->with('success', 'Offering deleted and removed from class.');
    }

    /**
     * Clear all course offerings.
     */
    public function clearAll()
    {
        CourseOffering::truncate();
        return redirect()->route('courseoffering.selected')->with('success', 'All offerings have been cleared.');
    }
}

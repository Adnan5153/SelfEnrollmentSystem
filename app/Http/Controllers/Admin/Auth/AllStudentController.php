<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\AllStudent;
use App\Models\ParentModel;
use Illuminate\Http\Request;

class AllStudentController extends Controller
{
    /**
     * Display a listing of all students.
     */
    public function index()
    {
        // Fetch students with pagination
        $students = AllStudent::paginate(10);
        return view('admin.layouts.allstudent', compact('students'));
    }

    /**
     * Display a listing of all parents.
     */
    public function showParents()
    {
        // Fetch parents with pagination
        $parents = ParentModel::paginate(10);
        return view('admin.layouts.allparents', compact('parents'));
    }

    /**
     * Update the specified student in the database.
     */
    public function update(Request $request, $student_id)
    {
        // Fetch the student record
        $student = AllStudent::where('student_id', $student_id)->firstOrFail();

        // Validate student data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class' => 'required|string',
            'section' => 'required|string',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'religion' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:allstudents,email,' . $student_id . ',student_id',
        ]);

        // Ensure student has a parent (assign default parent if none)
        if (!$student->parent_id) {
            $defaultParent = ParentModel::firstOrCreate(
                ['parent_email' => 'default.parent@institution.edu'],
                [
                    'father_name'       => 'Default Father',
                    'mother_name'       => 'Default Mother',
                    'father_occupation' => 'N/A',
                    'mother_occupation' => 'N/A',
                    'phone_number'      => '+880 1XXX-XXXXXX',
                    'present_address'   => 'Default Address',
                    'permanent_address' => 'Default Address',
                ]
            );
            $validatedData['parent_id'] = $defaultParent->id;
        }

        // Update student details
        $student->update($validatedData);

        return redirect()->route('allstudent.index')->with('success', 'Student information updated successfully.');
    }

    /**
     * Update the specified parent in the database.
     */
    public function updateParent(Request $request, $parent_id)
    {
        // Fetch the parent record
        $parent = ParentModel::where('id', $parent_id)->firstOrFail();

        // Validate parent data
        $validatedData = $request->validate([
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'parent_email' => 'required|email|max:255|unique:allparents,parent_email,' . $parent_id . ',id',
        ]);

        // Update parent details
        $parent->update($validatedData);

        return redirect()->route('allparents.index')->with('success', 'Parent information updated successfully.');
    }

    /**
     * Delete a student and related parent data if applicable.
     */
    public function destroy($student_id)
    {
        try {
            $student = AllStudent::where('student_id', $student_id)->firstOrFail();
            $parent = $student->parent;

            // Only delete parent if it's not the default parent and has no other students
            if ($parent && $parent->parent_email !== 'default.parent@institution.edu' && $parent->students()->count() === 1) {
                $parent->delete();
            }

            $student->delete();

            return redirect()->route('allstudent.index')->with('success', 'Student deleted.');
        } catch (\Exception $e) {
            return redirect()->route('allstudent.index')->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }


    /**
     * Delete a parent separately.
     */
    public function destroyParent($parent_id)
    {
        // Find parent record
        $parent = ParentModel::where('id', $parent_id)->firstOrFail();

        // Ensure no students are still linked to this parent before deleting
        if ($parent->students()->exists()) {
            return redirect()->route('allparents.index')->with('error', 'Cannot delete parent with linked students.');
        }

        $parent->delete();

        return redirect()->route('allparents.index')->with('success', 'Parent deleted successfully.');
    }
}

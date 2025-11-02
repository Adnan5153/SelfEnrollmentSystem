<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\AllTeacher;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class AllTeacherController extends Controller
{
    /**
     * Display a listing of all teachers.
     */
    public function index()
    {
        $teachers = AllTeacher::with('class')->paginate(10); // eager load class

        return view('admin.layouts.allteachers', ['teachers' => $teachers]);
    }


    /**
     * Update a specific teacher's details in the database.
     *
     * @param  Request  $request
     * @param  string  $id
     */
    public function update(Request $request, $id)
    {
        // Validation rules
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'section' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'joining_date' => 'required|date',
            'nid_number' => 'nullable|integer',
            'religion' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:allteachers,email,' . $id . ',id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        // Fetch the class name from the ClassModel using the class_id
        $classData = ClassModel::findOrFail($validatedData['class_id']);
        $className = $classData->class_name;

        // Find the teacher using id
        $teacher = AllTeacher::findOrFail($id);

        // Update teacher data
        $teacher->update($validatedData);

        // Redirect to the list page with a success message
        return redirect()->route('allteachers.index')->with('success', 'Teacher updated successfully.');
    }


    /**
     * Remove a specific teacher from the database.
     *
     * @param  string  $id
     */
    public function destroy($id)
    {
        // Find and delete the teacher by their ID
        $teacher = AllTeacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('allteachers.index')->with('success', 'Teacher deleted successfully.');
    }
}

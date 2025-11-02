<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Teacher;
use App\Models\AllTeacher;
use App\Models\ClassModel;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AddTeacherController extends Controller
{
    /**
     * Show the form for adding a new teacher.
     */

    public function create()
    {
        $classes = ClassModel::all();
        $departments = Department::all();
        return view('admin.layouts.addteacher', compact('classes', 'departments'));
    }


    /**
     * Store a newly created teacher in the database and register them.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class_section' => 'required', // Validate merged Class & Section dropdown
            'department_id' => 'required|exists:departments,id',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'joining_date' => 'required|date',
            'nid_number' => 'nullable|integer',
            'religion' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:allteachers,email|unique:teachers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        // Extract class_id and section from merged input
        list($classId, $section) = explode('|', $request->class_section);

        // Ensure the selected section is valid for the chosen class
        $validSection = ClassModel::where('id', $classId)->where('section', $section)->exists();
        if (!$validSection) {
            return back()->withErrors(['class_section' => 'Invalid class-section selection.']);
        }

        // Create the teacher record in `allteachers` table
        AllTeacher::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'class_id' => $classId,
            'section' => $section,
            'department_id' => $validatedData['department_id'],
            'gender' => $validatedData['gender'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'joining_date' => $validatedData['joining_date'],
            'nid_number' => $validatedData['nid_number'],
            'religion' => $validatedData['religion'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ]);

        // Automatically register teacher in `teachers` table
        Teacher::create([
            'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make('12345678'), // Default password
            'class_id' => $classId,
            'section' => $section,
        ]);

        return redirect()->route('addteacher.create')->with('success', 'Teacher has been successfully added and registered.');
    }
}

<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Student;
use App\Models\AllStudent;
use App\Models\ClassModel;
use App\Models\Department;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AddStudentController extends Controller
{
    public function create()
    {
        $departments = Department::all();
        $classes = ClassModel::all();
        return view('admin.layouts.addstudent', compact('classes', 'departments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'class_id'          => 'required|exists:classes,id',
            'gender'            => 'required|string|max:10',
            'date_of_birth'     => 'required|date',
            'student_id'        => 'required|numeric|unique:allstudents,student_id',
            'department_id'     => 'required|exists:departments,id',
            'religion'          => 'required|string|max:50',
            'email'             => 'required|email|max:255|unique:allstudents,email|unique:students,email',
        ]);

        DB::beginTransaction();

        try {
            $classData = ClassModel::findOrFail($validatedData['class_id']);

            // Get or create a static/default parent for all students
            $parent = ParentModel::firstOrCreate(
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

            AllStudent::create([
                'student_id'    => $validatedData['student_id'],
                'first_name'    => $validatedData['first_name'],
                'last_name'     => $validatedData['last_name'],
                'class_id'      => $validatedData['class_id'],
                'class'         => $classData->class_name,
                'section'       => $classData->section,
                'gender'        => $validatedData['gender'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'department_id' => $validatedData['department_id'],
                'religion'      => $validatedData['religion'],
                'email'         => $validatedData['email'],
                'parent_id'     => $parent->id,
            ]);

            // Ensure Student model/migration includes department_id
            Student::create([
                'name'          => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'email'         => $validatedData['email'],
                'password'      => Hash::make('12345678'),
                'class_id'      => $validatedData['class_id'],
                'section'       => $classData->section,
                'department_id' => $validatedData['department_id'],
            ]);

            DB::commit();

            return redirect()->route('register.student.and.parent')
                ->with('success', 'Student added successfully. Student account created with default password: 12345678');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add student error: ' . $e->getMessage());

            return redirect()->route('register.student.and.parent')
                ->with('error', 'An error occurred. Please try again.');
        }
    }
}

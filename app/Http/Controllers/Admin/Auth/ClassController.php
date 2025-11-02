<?php

namespace App\Http\Controllers\Admin\Auth; // Correct namespace

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel; // Correct model import

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::all();
        return view('admin.layouts.allclasses', compact('classes'));
    }

    public function create()
    {
        return view('admin.layouts.addclass');
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        ClassModel::create([
            'class_name' => $request->class_name,
            'section' => $request->section,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class added successfully.');
    }

    public function edit($id)
    {
        $class = ClassModel::findOrFail($id);
        return view('admin.layouts.modal.editclass', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);

        $request->validate([
            'class_name' => 'required|string|max:255|unique:classes,class_name,' . $id,
            'section' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $class->update([
            'class_name' => $request->class_name,
            'section' => $request->section,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}

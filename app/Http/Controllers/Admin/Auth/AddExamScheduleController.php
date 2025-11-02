<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamSchedule;
use App\Models\ClassModel;
use App\Models\Subject;

class AddExamScheduleController extends Controller
{
    /**
     * Show the form for creating exam schedule.
     */
    public function index()
    {
        $classes = ClassModel::all();
        $subjects = Subject::all(); // All subjects for dropdown
        $examSchedules = ExamSchedule::with(['class', 'subject'])->get();

        return view('admin.layouts.addexamschedule', compact('classes', 'subjects', 'examSchedules'));
    }

    /**
     * Store a new exam schedule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:20',
        ], [
            'exam_date.after_or_equal' => 'Exam date cannot be in the past.',
            'end_time.after' => 'End time must be after start time.',
        ]);

        // Prevent exam schedule conflicts
        $conflict = ExamSchedule::where('class_id', $request->class_id)
            ->where('exam_date', $request->exam_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'This exam schedule conflicts with another exam.'])->withInput();
        }

        ExamSchedule::create($request->all());

        return redirect()->route('examschedule.index')->with('success', 'Exam schedule added successfully.');
    }

    /**
     * Show all exam schedules.
     */
    public function show()
    {
        $examSchedules = ExamSchedule::with(['class', 'subject'])->get();
        $classes = ClassModel::all();
        $subjects = Subject::all();
        return view('admin.layouts.examschedulelist', compact('examSchedules', 'classes', 'subjects'));
    }


    /**
     * Show the form to edit an exam schedule.
     */
    public function edit($id)
    {
        $examSchedule = ExamSchedule::findOrFail($id);
        $classes = ClassModel::all();
        $subjects = Subject::all(); // Show all subjects in edit form

        return view('admin.layouts.modal.editexamschedule', compact('examSchedule', 'classes', 'subjects'));
    }

    /**
     * Update an exam schedule.
     */
    public function update(Request $request, $id)
    {
        $examSchedule = ExamSchedule::findOrFail($id);

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:20',
        ], [
            'exam_date.after_or_equal' => 'Exam date cannot be in the past.',
            'end_time.after' => 'End time must be after start time.',
        ]);

        $examSchedule->update($request->all());

        return redirect()->route('examschedule.list')->with('success', 'Exam schedule updated successfully.');
    }

    /**
     * (Optional) API endpoint for JS: Get subjects by class.
     * You can remove this if you're no longer filtering subjects.
     */
    public function getSubjectsByClass(Request $request)
    {
        // Not used anymore, but kept for backward compatibility.
        $subjects = Subject::all();
        return response()->json($subjects);
    }
}

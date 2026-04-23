<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StudentSubjectController extends Controller
{
    /**
     * Show list of offered and enrolled subjects for this student.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Offered subjects (for student's class)
        $offeredSubjects = $student->class
            ? $student->class->offered_subjects()->with(['credit', 'prerequisites', 'class_routines.teacher'])->get()
            : collect();

        // Already enrolled subjects
        $enrolledSubjects = method_exists($student, 'subjects')
            ? $student->subjects()->with(['credit', 'prerequisites', 'class_routines.teacher'])->get()
            : collect();

        return view('student.layouts.subjects', [
            'offeredSubjects' => $offeredSubjects,
            'enrolledSubjects' => $enrolledSubjects,
        ]);
    }

    /**
     * Bulk enrollment: Enroll student in multiple courses at once.
     */
    public function enrollBulk(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        // Log all request data for debugging
        Log::info('Enrollment request received', [
            'student_id' => $student->id,
            'student_name' => $student->name,
            'all_request_data' => $request->all(),
            'selected_courses_input' => $request->input('selected_courses'),
        ]);
        
        $subjectIds = $request->input('selected_courses', []);

        if (empty($subjectIds)) {
            Log::warning('No courses selected', [
                'student_id' => $student->id,
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'No courses selected.');
        }

        // Exclude courses already enrolled
        $alreadyEnrolled = $student->subjects()->pluck('subjects.id')->toArray();
        $toEnroll = array_diff($subjectIds, $alreadyEnrolled);

        if (!empty($toEnroll)) {
            try {
                // Start explicit transaction
                DB::beginTransaction();
                
                // Attach subjects with timestamps
                // The withTimestamps() in the relationship will automatically add created_at and updated_at
                $student->subjects()->attach($toEnroll);
                
                // Log the enrollment for debugging
                Log::info("Student enrollment", [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'enrolled_subjects' => $toEnroll,
                    'timestamp' => now()
                ]);
                
                // Verify the data was inserted BEFORE commit
                $enrolledCount = DB::table('student_subject')
                    ->where('student_id', $student->id)
                    ->whereIn('subject_id', $toEnroll)
                    ->count();
                
                Log::info("Enrollment verification (before commit)", [
                    'expected_count' => count($toEnroll),
                    'actual_count' => $enrolledCount
                ]);
                
                // Explicitly commit the transaction
                DB::commit();
                
                Log::info("Transaction committed successfully");
                
                // Verify AFTER commit
                $enrolledCountAfter = DB::table('student_subject')
                    ->where('student_id', $student->id)
                    ->whereIn('subject_id', $toEnroll)
                    ->count();
                
                Log::info("Enrollment verification (after commit)", [
                    'expected_count' => count($toEnroll),
                    'actual_count_after' => $enrolledCountAfter
                ]);
                
                // Refresh the student model to ensure relationships are up-to-date
                $student->refresh();
                
                // Clear any cached relationships
                $student->load('subjects');
                
                return redirect()->back()->with('success', "Successfully enrolled in " . count($toEnroll) . " course(s)!");
                
            } catch (\Exception $e) {
                // Rollback on error
                DB::rollBack();
                
                Log::error("Enrollment failed", [
                    'student_id' => $student->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->back()->with('error', 'Failed to enroll in courses. Please try again.');
            }
        }

        return redirect()->back()->with('info', 'You are already enrolled in the selected courses.');
    }

    /**
     * Drop a course from the student's enrolled subjects.
     */

    public function drop($subjectId)
    {
        $student = Auth::guard('student')->user();

        // Detach only if the student is enrolled
        if ($student->subjects()->where('subjects.id', $subjectId)->exists()) {
            $student->subjects()->detach($subjectId);
        }

        return redirect()->back()->with('success', 'Course dropped successfully!');
    }
}

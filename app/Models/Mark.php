<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Mark extends Model
{
    protected $table = 'marks';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'marks',
        'remarks'
    ];

    /**
     * The "booted" method of the model.
     * Automatically sync credits when a grade is given (mark is created, updated, or deleted)
     * Credits are earned when a PASSING grade is given (grade above F)
     */
    protected static function booted()
    {
        // When a mark is created or updated (grade is given), sync credits from all results
        static::saved(function ($mark) {
            // Load relationships if not already loaded
            if (!$mark->relationLoaded('student')) {
                $mark->load('student');
            }
            if (!$mark->relationLoaded('subject')) {
                $mark->load('subject.credit');
            }
            
            if ($mark->student) {
                // Sync credits from all passed subjects (including this new/updated mark)
                // This ensures credits are earned immediately when a passing grade is given
                $mark->student->syncCreditsFromResults();
                
                if ($mark->isPassingGrade() && $mark->subject && $mark->subject->credit) {
                    $credits = (float) $mark->subject->credit->credit_hour;
                    Log::info("Auto-credit sync: Student {$mark->student->name} (ID: {$mark->student->id}) received passing grade for {$mark->subject->name}. Credits earned: {$credits}. Total credits: {$mark->student->credit_completed}");
                } else {
                    Log::info("Auto-credit sync: Student {$mark->student->name} (ID: {$mark->student->id}) received grade for {$mark->subject->name}. Credits recalculated: {$mark->student->credit_completed}");
                }
            }
        });

        // When a mark is deleted, sync credits to remove its contribution
        static::deleted(function ($mark) {
            // Load student relationship if available
            $studentId = $mark->student_id;
            $student = \App\Models\Student::find($studentId);
            
            if ($student) {
                // Recalculate credits without the deleted mark
                $student->syncCreditsFromResults();
                
                Log::info("Auto-credit sync: Mark deleted for student {$student->name} (ID: {$student->id}). Credits recalculated: {$student->credit_completed}");
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the grade for this mark
     * @return Grade|null
     */
    public function getGrade()
    {
        return Grade::where('min_marks', '<=', $this->marks)
            ->where('max_marks', '>=', $this->marks)
            ->first();
    }

    /**
     * Check if the student passed (grade above F)
     * Credits are earned ONLY when passing grade is given (not F)
     * @return bool
     */
    public function isPassingGrade()
    {
        $grade = $this->getGrade();
        return $grade && $grade->grade !== 'F';
    }
}

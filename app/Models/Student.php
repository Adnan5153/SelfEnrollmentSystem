<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Student extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'students';

    protected $hidden = ['password', 'remember_token'];

    protected $fillable = [
        'name',
        'email',
        'year',
        'credit_completed',
        'email_verified_at',
        'password',
        'class_id',
        'section',
        'department_id',
        'remember_token'
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * Award credits to the student
     * @param float $credits Number of credits to award
     * @return bool Success status
     */
    public function awardCredits($credits)
    {
        // Use DB-level increment to avoid race conditions and ensure persistence
        // Cast to float then to appropriate numeric for DB (column is bigInteger)
        $delta = (float) $credits;
        // If credits can be fractional (e.g., 1.5), consider changing column type to decimal.
        // For now, round to nearest integer to match bigInteger schema.
        $incrementBy = (int) round($delta);
        if ($incrementBy === 0) {
            return true;
        }
        $updated = static::where('id', $this->id)->increment('credit_completed', $incrementBy);
        if ($updated) {
            // Refresh the in-memory value to reflect DB state
            $this->refresh();
            return true;
        }
        return false;
    }

    /**
     * Check if student has already earned credits for a specific subject
     * Credits are earned only when student has a passing grade (not F)
     * Checks ALL marks for the subject to see if student has ever passed it
     * @param int $subjectId Subject ID to check
     * @return bool
     */
    public function hasCreditsForSubject($subjectId)
    {
        // Check all marks for this subject to see if student has ever passed
        $marks = $this->marks()
            ->where('subject_id', $subjectId)
            ->get();

        if ($marks->isEmpty()) {
            return false;
        }

        // Student has earned credits if they have ANY passing grade for this subject
        foreach ($marks as $mark) {
            if ($mark->isPassingGrade()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate actual earned credits from passed subjects
     * This recalculates credits based on marks with passing grades
     * Credits are earned when a PASSING grade is given (grade above F)
     * Counts credits for ALL passed subjects including prerequisites
     * @return float
     */
    public function calculateEarnedCredits()
    {
        $marks = $this->marks()
            ->with('subject.credit')
            ->get();

        $totalCredits = 0;
        
        // Track subjects to avoid double-counting (use latest mark for each subject)
        $subjectCredits = [];
        
        foreach ($marks as $mark) {
            // Credits are earned ONLY when a PASSING grade is given (not F)
            // Check if the mark has a passing grade
            if ($mark->isPassingGrade() && $mark->subject && $mark->subject->credit) {
                $subjectId = $mark->subject_id;
                $credits = (float) $mark->subject->credit->credit_hour;
                
                // Use the latest mark for each subject (in case of retakes)
                // This ensures credits are only counted once per subject
                if (!isset($subjectCredits[$subjectId]) || $mark->id > $subjectCredits[$subjectId]['mark_id']) {
                    $subjectCredits[$subjectId] = [
                        'credits' => $credits,
                        'mark_id' => $mark->id
                    ];
                }
            }
        }
        
        // Sum up unique subject credits (includes all passed prerequisites)
        foreach ($subjectCredits as $data) {
            $totalCredits += $data['credits'];
        }
        
        return $totalCredits;
    }

    /**
     * Sync credit_completed field with actual earned credits from all passed subjects
     * This recalculates credits based on all marks with passing grades (including prerequisites)
     * and updates the credit_completed field to match
     * @return bool Success status
     */
    public function syncCreditsFromResults()
    {
        $calculatedCredits = $this->calculateEarnedCredits();
        $calculatedCreditsInt = (int) round($calculatedCredits);
        
        // Update credit_completed to match calculated credits from all passed subjects
        $updated = static::where('id', $this->id)->update(['credit_completed' => $calculatedCreditsInt]);
        
        if ($updated) {
            // Refresh the in-memory value to reflect DB state
            $this->refresh();
            return true;
        }
        
        return false;
    }

    /**
     * Get list of subject IDs that the student has passed (with passing grades)
     * @return array
     */
    public function getPassedSubjectIds()
    {
        $marks = $this->marks()->with('subject')->get();
        $passedSubjectIds = [];

        foreach ($marks as $mark) {
            if ($mark->isPassingGrade()) {
                $passedSubjectIds[] = $mark->subject_id;
            }
        }

        return array_unique($passedSubjectIds);
    }
}

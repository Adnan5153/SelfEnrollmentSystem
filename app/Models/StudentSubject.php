<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'student_subject';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'subject_id',
    ];

    /**
     * Get the student that owns the enrollment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subject that is enrolled.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

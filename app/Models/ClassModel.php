<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'class_name',
        'section',
        'capacity',
    ];

    /**
     * Subjects offered to this class.
     */
    public function offered_subjects()
    {
        // Use the custom Pivot model
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
            ->using(ClassSubject::class);
    }

    public function course_offerings()
    {
        return $this->hasMany(CourseOffering::class);
    }

    public function latest_course_offerings()
    {
        return $this->hasMany(CourseOffering::class)->latest();
    }
}

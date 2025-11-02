<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    // Year options constants
    const YEAR_OPTIONS = [
        '1st Year' => '1st Year',
        '2nd Year' => '2nd Year',
        '3rd Year' => '3rd Year',
        '4th Year' => '4th Year',
        'Technical Electives' => 'Technical Electives'
    ];

    protected $fillable = [
        'name',
        'subject_code',
        'year',
        'teacher_id',
        'credit_id',
        'department_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Direct Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The teacher assigned to this subject.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * The credit type or value assigned to this subject.
     */
    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    /**
     * The department this subject belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Timetable / Academic Schedule
    |--------------------------------------------------------------------------
    */

    /**
     * Class routines for this subject.
     */
    public function class_routines()
    {
        return $this->hasMany(ClassRoutine::class);
    }

    /**
     * Exam schedules for this subject.
     */
    public function examschedules()
    {
        return $this->hasMany(Examschedule::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Subject Enrollment / Assignment
    |--------------------------------------------------------------------------
    */

    /**
     * Classes where this subject is offered.
     */
    public function offered_in_classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_subject', 'subject_id', 'class_id');
    }

    /**
     * Students enrolled in this subject.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subject');
    }

    /*
    |--------------------------------------------------------------------------
    | Prerequisite Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Subjects that must be completed before this subject (prerequisites).
     */
    public function prerequisites()
    {
        return $this->belongsToMany(
            Subject::class,
            'prerequisites',
            'subject_id',
            'prerequisite_id'
        )->withPivot('required_credits');
    }

    /**
     * Subjects that depend on this subject as a prerequisite.
     */
    public function isPrerequisiteFor()
    {
        return $this->belongsToMany(
            Subject::class,
            'prerequisites',
            'prerequisite_id',
            'subject_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Optional One-to-One Class Binding
    |--------------------------------------------------------------------------
    */

    /**
     * (Optional) The main class if this subject has a dedicated class_id column.
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function course_offerings()
    {
        return $this->hasMany(CourseOffering::class);
    }

    /**
     * Marks/grades for this subject.
     */
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}

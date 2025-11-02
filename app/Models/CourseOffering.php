<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseOffering extends Model
{
    use HasFactory;

    protected $table = 'course_offerings';

    protected $fillable = [
        'class_id',
        'subject_id',
        'enforce_prereq',
        'credit_hour',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassSubject extends Pivot
{
    protected $table = 'class_subject';

    protected $fillable = [
        'class_id',
        'subject_id',
        // Add other fields here if your table has them (like year, semester, etc.)
    ];

    public $timestamps = false; // If your pivot table does not have timestamps

    // Relationships
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}

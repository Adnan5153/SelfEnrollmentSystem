<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examschedule extends Model
{
	use HasFactory;

	protected $table = 'examschedules';

	protected $fillable = [
		'class_id',
		'subject_id',
		'exam_date',
		'start_time',
		'end_time',
		'room_number'
	];

	public function class()
	{
		return $this->belongsTo(ClassModel::class);
	}

	public function subject()
	{
		return $this->belongsTo(Subject::class);
	}
}

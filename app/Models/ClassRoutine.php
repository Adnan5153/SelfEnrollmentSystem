<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoutine extends Model
{
	use HasFactory;
	protected $table = 'class_routines';
	protected $fillable = [
		'subject_id',
		'teacher_id',
		'day_of_week',
		'start_time',
		'end_time',
		'room_number'
	];


	public function subject()
	{
		return $this->belongsTo(Subject::class);
	}

	public function teacher()
	{
		return $this->belongsTo(Teacher::class);
	}
}

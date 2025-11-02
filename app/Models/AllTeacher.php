<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allteacher extends Model
{
	protected $table = 'allteachers';

	protected $fillable = [
		'class_id',
        'department_id',
		'first_name',
		'last_name',
		'section',
		'gender',
		'date_of_birth',
		'joining_date',
		'nid_number',
		'religion',
		'email',
		'phone',
		'address'
	];

	public function class()
	{
		return $this->belongsTo(ClassModel::class);
	}
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

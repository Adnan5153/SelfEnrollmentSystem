<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditPerYear extends Model
{
    protected $table = 'credit_per_years';

    protected $fillable = [
        'department_id',
        'year',
        'required_credits',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

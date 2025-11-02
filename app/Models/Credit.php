<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_type', 
        'credit_hour'
    ];
    
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}

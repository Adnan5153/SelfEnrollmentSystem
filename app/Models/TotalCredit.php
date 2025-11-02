<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalCredit extends Model
{
    protected $table = 'totalcredits';

    protected $fillable = [
        'total_credit',
    ];
}

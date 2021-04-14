<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EDisco extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','code','comm_amt','charge','ref_amt'
    ];
}

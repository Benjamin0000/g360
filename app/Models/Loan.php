<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'id',
        'user_id',
        'gnumber',
        'amount',
        'total_return',
        'exp_days',
    ];
}

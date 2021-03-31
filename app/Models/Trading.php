<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trading extends Model
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
        'name',
        'amount',
        'returned',
        'amt_return',
        'interest',
        'interest_amt',
        'interest_returned',
        'exp_days',
        'last_added'
    ];
}

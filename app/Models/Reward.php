<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
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
        'name',
        'user_id',
        'gnumber',
        'rank_id',
        'loan_amount',
        'loan_month',
        'lmp_amount',
        'lmp_month',
        'loan_grace_month',
        'loan_interest',
        'loan_grace_interest'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epin extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'pkg_id',
        'code',
        'user_id',
        'gnumber',
        'used_by',
        'used_date'
    ];
}

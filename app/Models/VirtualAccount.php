<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'reference', 
        'number',
        'bvn'
    ];
}

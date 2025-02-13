<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    
    use HasFactory;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'prize',
        'loan',
        'pv',
        'loan_exp_m',
        'total_lmp',
        'lmp_months',
        'loan_interest',
        'loan_g_interest',
        'loan_g_exp_m'
    ];
    /**
     * Get next rank
     *
     */    
    public function next(){
        return self::where('id', '>', $this->id)->orderBy('id','asc')->first();
    }
}

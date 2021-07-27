<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectHistory extends Model
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
        'user_id',
        'name',
        'address',
        'product_id',
        'amount',
        'fee',
        'pin_code',
        'operator_name',
        'customer_reference',
        'reference',
        'meter_no',
    ];
}

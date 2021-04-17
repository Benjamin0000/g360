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
        'garant',
        'user_id',
        'gnumber',
        'amount',
        'returned',
        'total_return',
        'garant_amt',
        'interest',
        'exp_months',
        'grace_months',
        'g_approve',
        'extra',
        'expiry_date',
        'grace_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

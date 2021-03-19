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
        'garant_one',
        'garant_two',
        'user_id',
        'gnumber',
        'amount',
        'returned',
        'garant_amt_one',
        'garant_amt_two',
        'interest',
        'exp_months',
        'grace_months',
        'approve_one',
        'approve_two',
        'extra',
        'expiry_date',
        'grace_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

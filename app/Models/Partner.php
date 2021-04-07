<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Partner extends Model
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
        'type',
        's_credit',
        'f_credit',
        'e_credit',
        'min_with'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function contracts()
    {
        return $this->hasMany(PContract::class, 'partner_id');
    }
    public function total_invested()
    {
        return PContract::where('partner_id', $this->id)->sum('amount');
    }
    public function total_currently_invested()
    {
        return PContract::where([
            ['partner_id', $this->id], 
            ['status', 0]
        ])->sum('amount');
    }
    public function total_expected_return()
    {
        return PContract::where([
            ['partner_id', $this->id], 
            ['status', 0]
        ])->sum('total_return');
    }
    public function total_received_roi()
    {
        return PContract::where([
            ['partner_id', $this->id], 
            ['status', 0]
        ])->sum('returned');
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PContract extends Model
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
        'partner_id',
        'amount',
        'total_return',
        'returned',
        'months'
    ];
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}

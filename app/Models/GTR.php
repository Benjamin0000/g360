<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class GTR extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'pay_back',
        'r_count',
        'r_hours',
        'g_hours',
        'total_ref',
        'level',
    ];
    public function gsTeamType($amt, $type)
    {
        return GsClub::where([
            ['gbal', $amt],
            ['g', $type]
        ])->count();
    }
    public function totalDefault()
    {
        return GsClub::where([
            ['def', 1],
            ['gbal', $this->amount]
        ])->count();
    }
}

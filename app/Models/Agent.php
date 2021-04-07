<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
class Agent extends Model
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
        'ref_by',
        'status',
        'state_id',
        'city_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function fbronz_coin()
    {
        $set = AgentSetting::first();
        if($this->type == 1)
            $fbc = $set->sg_pfbc;
        else
            $fbc = $set->ag_pfbc;
        return $this->fbronz_deca/$fbc;
    }
    public function gsilver_coin()
    {
        $set = AgentSetting::first();
        if($this->type == 1)
            $gsc = $set->sg_pgsc;
        else
            $gsc = $set->ag_pgsc;
        return $this->gsilver_deca/$gsc;
    }
    public function rgold_coin()
    {
        $set = AgentSetting::first();
        if($this->type == 1)
            $rgc = $set->sg_prgc;
        else
            $rgc = $set->ag_prgc;
        return $this->rgold_deca/$rgc;
    }
    public function posD_coin()
    {
        $set = AgentSetting::first();
        if($this->type == 1)
            $pos_c = $set->sg_posc;
        else
            $pos_c = $set->ag_posc;
        return $this->pos_deca/$pos_c;
    }
    public function totalBalance()
    {
        $deca = $this->fbronz_deca + 
        $this->gsilver_deca + 
        $this->rgold_deca + 
        $this->pos_deca;
        return $deca;
    }
    public function totalValidReferrer()
    {
        return User::where([ 
            ['ref_gnum', $this->user->gnumber], 
            ['pkg_id', '>' , 1] 
        ])->count();
    }
    public function creditAgent($type, $amt)
    {
        switch($type){
            case 'f': 
                $this->fbronz_deca += $amt;
            break;
            case 'g':
                $this->gsilver_deca += $amt;
            break;
            case 'r':
                $this->rgold_deca += $amt;
            break;
            case 'pos': 
                $this->pos_deca += $amt;
            break;
            default:
                throw new Exception("invalid agent account");
        }
        $this->balance += $amt;
        $this->credited += $amt;
        $this->save();
    }
}

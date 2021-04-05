<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
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
        'gnumber',
        'placed_by',
        'username',
        'title',
        'fname',
        'lname',
        'ref_gnum',
        'email',
        'phone',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get Loan Balance
     * @return bool
    */      
    public function loanBalance()
    {
        $loan = Loan::where([ 
            ['user_id', $this->id],
            ['status', 0]
        ]);
        if($loan->exists()){
            $returned = $loan->sum('returned');
            $total_return = $loan->sum('total_return');
            return $returned - $total_return;
        }   
        return 0;
    }
    /**
     * Determine if users can earn from a ref level
     * @return bool
    */  
    public function canEarnFromLevel($level)
    {
        $package = Package::find($this->pkg_id);
        if($package){   
            if($package->gen >= $level)
                return true;
        }
        return false;
    }
    /**
     * Get users signed up package
     * @return object
    */  
    public function package()
    {
        return $this->belongsTo(Package::class, 'pkg_id');
    }
    /**
     * Get users package upgrade history
     * @return object
    */      
    public function upgrade()
    {
        return $this->hasOne(UpgradeHistory::class, 'user_id', 'id');
    }
    /**
     * Find yet to be paid loan
     * @return bool
    */      
    public function haveUnPaidLoan()
    {
        return Loan::where([
            ['user_id', $this->id], 
            ['status', 0] 
        ])->exists();
    }
    /**
     * Super associate bonus
     * @return object
     */   
    public function superAssoc()
    {
        return $this->hasOne(SuperAssociate::class, 'user_id', 'id');
    }
    /**
     * Get users rank
     * @return object
     */   
    public function rank()
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }
    /**
     * Get Users Loan
     * @return object
    */      
    public function loan()
    {
        return $this->hasOne(Loan::class, 'user_id', 'id');
    }
    /**
     * Get total direct valid referrals
     * @return int
    */   
    public function totalValidRef()
    {
        return self::where([ 
            ['ref_gnum', $this->gnumber], 
            ['pkg_id', '>', 1] 
        ])->count();
    }
    /**
     * ppp
     * @return int
    */
    public function ppp()
    {
        return $this->hasOne(PPP::class, 'user_id', 'id');
    }
    /**
     * Get users partnership
     * @return int
    */
    public function partner()
    {
        return $this->hasOne(Partner::class, 'user_id');
    }
     /**
     * Agent
     * @return int
    */
    public function agent()
    {
        return $this->hasOne(Agent::class, 'user_id');
    }
}

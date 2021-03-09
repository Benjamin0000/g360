<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    public function canEarnFromLevel($level)
    {
        $package = Package::find($this->pkg_id);
        if($package){   
            if($package->gen >= $level)
                return true;
        }
        return false;
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'pkg_id');
    }

    
    public function upgrade()
    {
        return $this->belongsTo(UpgradeHistory::class, 'id', 'user_id');
    }

    public function haveUnPaidLoan()
    {
        return Loan::where([
             ['user_id', $this->id], 
             ['status', 0] 
        ])->exists();
        
    }
}

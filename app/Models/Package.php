<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\UpgradeHistory;
use App\Models\WalletHistory;
use App\Http\Helpers;
class Package extends Model
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
        'amount',
        'pv',
        'h_token',
        'ref_pv',
        'ref_h_token',
        'ref_percent',
        'insurance',
        'gen'
    ];

    public function activate($insurance = 'none', $paymethod = 'free')
    {
        $last_pkg_id = 7;
        $free_pkg_id = 1;
        $basic_pkg = 2;
        $cash_back_percent = 8;
        $basic_pv = self::where('id', 2)->first()->pv;
        $user = Auth::user();
        if($user->pkg_id == $last_pkg_id) return false; // user has reached the last package
        if($this->id <= $user->pkg_id) return false; // can't upgrade to the same package or less
        $upgh = UpgradeHistory::create([
            'id'=>Helpers::genTableId(UpgradeHistory::class),
            'user_id'=>$user->id,
            'gnumber'=>$user->gnumber,
            'from'=>$user->pkg_id,
            'to'=>$this->id,
            'pay_method'=>$paymethod
        ]);
        $user->pkg_id = $this->id;
        if($this->id == $free_pkg_id){
            $user->save();
            return true;
        }
        $user->h_token+=$this->h_token; //asign health token
        $pv_value = ($basic_pv*$this->id) - $this->pv; 
        $user->pv+=$pv_value; //asign pv
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$this->h_token,
            'gnumber'=>$user->gnumber,
            'name'=>'h_token',
            'type'=>'credit',
            'description'=>$this->h_token .' Health token received from '.ucfirst($this->name).' package'
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$pv_value,
            'gnumber'=>$user->gnumber,
            'name'=>'pv',
            'type'=>'credit',
            'description'=>$pv_value.' point value received from '.ucfirst($this->name).' package'
        ]);
        $old_package = self::find($upgh->from);
        if($old_package)
            $amount = $this->amount - $old_package->amount;
        else
            $amount = $this->amount;
        Helpers::shareRefCommission($this->id, $amount, $user->gnumber);
        if($insurance == 1){
            //activate insurance
        }else{
            $cash_back = ($cash_back_percent/100)*$amount;
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$cash_back,
                'gnumber'=>$user->gnumber,
                'name'=>'p_wallet',
                'type'=>'credit',
                'description'=>$cash_back.'received from '.ucfirst($this->name).' package cashback'
            ]);
            $user->p_wallet+=$cash_back;
            $user->save();
        }
        return true;
    }
}

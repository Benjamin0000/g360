<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers;
use Carbon\Carbon;
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

    public function activate(User $user, $insurance = 'none', $paymethod = 'free')
    {
        $last_pkg_id = 7;
        $free_pkg_id = 1;
        $basic_pkg_id = 2;
        $super_pkg_id = 4;
        $cash_back_percent = 8;
        $pend_balance = Helpers::PEND_BALANCE;
        $h_token = Helpers::HEALTH_TOKEN;
        $cpv = Helpers::CUM_POINT_VALUE;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $basic_pv = self::where('id', $basic_pkg_id)->first()->pv;

        if($user->pkg_id == $last_pkg_id) return false; // user has reached the last package
        if($this->id <= $user->pkg_id) return false; // can't upgrade to the same package or less
        $from = $user->pkg_id;
        $upgh = UpgradeHistory::create([
            'id'=>Helpers::genTableId(UpgradeHistory::class),
            'user_id'=>$user->id,
            'gnumber'=>$user->gnumber,
            'from'=>$from,
            'to'=>$this->id,
            'pay_method'=>$paymethod
        ]);
        $user->pkg_id = $this->id;
        if($this->id == $free_pkg_id){
            $user->save();
            return true;
        }

        if($from > 0)
            $from-=1;

        if($this->h_token)
            $user->$h_token+=$this->h_token; //asign health token

        $pv_value = $this->pv - ($basic_pv * $from);
        $user->$cpv+=$pv_value; //asign pv
        $user->save();
        if($this->h_token){
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$this->h_token,
                'gnumber'=>$user->gnumber,
                'name'=>$h_token,
                'type'=>'credit',
                'description'=>$this->h_token.' Health token received from '.ucfirst($this->name).' package'
            ]);
        }
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$pv_value,
            'gnumber'=>$user->gnumber,
            'name'=>$cpv,
            'type'=>'credit',
            'description'=>$pv_value.' point value received from '.ucfirst($this->name).' package'
        ]);
        $old_package = self::find($upgh->from);
        if($old_package)
            $amount = $this->amount - $old_package->amount;
        else
            $amount = $this->amount;

        $cash_back = ($cash_back_percent/100)*$amount;
        if($insurance == 'yes'){
            $h_token_price = (float)Helpers::getRegData('h_token_price');
            $h_token = $cash_back / $h_token_price;
            $user->h_token += $h_token;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$h_token,
                'gnumber'=>$user->gnumber,
                'name'=>'h_token',
                'type'=>'credit',
                'description'=>'health insurance'
            ]);
            //activate insurance
        }else{
            // $cash_back = ($cash_back_percent/100)*$amount;
            $user->$pend_balance += $cash_back;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$cash_back,
                'gnumber'=>$user->gnumber,
                'name'=>$pend_balance,
                'type'=>'credit',
                'description'=>$cur.$cash_back.' received from '.ucfirst($this->name).' package cashback'
            ]);
        }
        Helpers::shareRefCommission($this, $amount, $user);
        if(!PPP::where('user_id', $user->id)->exists()){
            PPP::create([
                'id'=>Helpers::genTableId(PPP::class),
                'user_id'=>$user->id,
            ]);
        }
        if($from == 0){
            Helpers::partnerUreward();
            if($ref_gnum = $user->ref_gnum){
                Helpers::rewardAgent($ref_gnum);
            }
            if(!GsClub::where('user_id', $user->id)->exists()){
                $gsclub = new GsClub();
                $gsclub->id = Helpers::genTableId(GsClub::class);
                $gsclub->user_id = $user->id;
                $gsclub->gbal = GTR::orderBy('id', 'ASC')->first()->amount;
                $gsclub->g = 1;
                $gsclub->lastg = Carbon::now();
                $gsclub->lastr = Carbon::now();
                $gsclub->save();
            }
        }
        if($this->lastPkg() && $user->pkg_balance > 0){
            $amt = $user->pkg_balance;
            $user->trx_balance += $amt;
            $user->pkg_balance = 0;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$amt,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_balance',
                'type'=>'credit',
                'description'=>$cur.$amt.' from PKG-Wallet'
            ]);
        }
        return true;
    }
    public function lastPkg()
    {
        $last_pkg = self::orderBy('id', 'DESC')->first();
        return $last_pkg->id == $this->id;
    }
}

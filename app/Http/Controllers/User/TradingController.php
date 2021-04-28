<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\TradePkg;
use App\Models\Trading;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
class TradingController extends G360
{
    /**
    * Creates a new controller instance
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = TradePkg::all();
        return view('user.trading.index', compact('packages'));
    }
    /**
     * Select Trading package
     *
     * @return \Illuminate\Http\Response
     */
    public function selectPkg(Request $request, $id)
    {
        $user = Auth::user();
        if($package = TradePkg::find($id)){
            $amt = $package->amount;
            if($user->trx_balance >= $amt){
                $pkg = Package::where('name', $package->min_pkg)->first();
                if($pkg){
                    if($user->pkg_id < $pkg->id)
                        return back()->with('error', "You must sign up for $pkg->name package to initiate this trade.");
                }else{
                    return back()->with('error', 'Sorry cannot trade at this time');
                }
                $user->trx_balance -= $amt;
                $user->save();
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$amt,
                    'gnumber'=>$user->gnumber,
                    'name'=>'trx_balance',
                    'type'=>'debit',
                    'description'=>self::$cur.$amt.
                    " for $package->name plan trading"
                ]);
                $interest_amt = ($package->interest/100)*$package->amount;
                Trading::create([
                    'id'=>Helpers::genTableId(Trading::class),
                    'user_id'=>$user->id,
                    'gnumber'=>$user->gnumber,
                    'name'=>$package->name,
                    'amount'=>$package->amount,
                    'amt_return'=>$package->amount/$package->exp_days,
                    'interest'=>$package->interest,
                    'interest_amt'=>$interest_amt,
                    'exp_days'=>$package->exp_days,
                    'last_added'=>Carbon::now(),
                ]);
                $pv = $package->ref_pv;
                $plan_name = $package->name;
                $formular = json_decode('['.$package->ref_percent.']',true);
                // self::sharePv($user, $pv, $plan_name);
                // self::shareCommission($user, $formular, $amt, $plan_name);
                return back()->with('success', 'Your trade with us has been initiated');
            }else{
                return back()->with('error', "Insufficient fund for this trading plan");
            }
        }else{
            return back()->with('error', 'Trading plan not found');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        $user = Auth::user();
        $trades = Trading::where('user_id', $user->id)->latest()->paginate(20);
        $tActive = Trading::where([['user_id', $user->id], ['status', 0]])->sum('amount');
        $tReceived = Trading::where([['user_id', $user->id], ['status', 1]]);
        $tReceived = $tReceived->sum('amount') + $tReceived->sum('interest_amt');
        $tTraded = Trading::where('user_id', $user->id)->sum('amount');
        return view('user.trading.history', compact('trades', 'tActive', 'tReceived', 'tTraded'));
    }
    /**
     *
     * Share Pv to uplines
     */
    public static function sharePv(User $user, $pv, $plan_name, $level = 1)
    {
        if($level > 15) return;
        $user = User::where([ ['gnumber', $user->ref_gnum], ['status', 1] ])->first();
        if($user){
            $user->cpv+=$pv;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$pv,
                'gnumber'=>$user->gnumber,
                'name'=>'cpv',
                'type'=>'credit',
                'description'=>"$pv PV from $plan_name plan level $level referral commission"
            ]);
            return self::sharePv($user, $pv, $level+1);
        }
    }
    /**
     *
     * Share Referral commission with uplines
     */
    public static function shareCommission(User $user, $formular, $amt, $plan_name, $level=1)
    {
        if($level > count($formular)) return;
        $profit = ($formular[$level-1] / 100) * $amt;
        if($user->placed_by)
            $user = User::where([ ['gnumber', $user->placed_by], ['status', 1] ])->first();
        else
            $user = User::where([ ['gnumber', $user->ref_gnum], ['status', 1] ])->first();
        if($user){
            $user->with_balance += $profit;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$profit,
                'gnumber'=>$user->gnumber,
                'name'=>self::$with_balance,
                'type'=>'credit',
                'description'=>self::$cur.$profit.
                " from $plan_name plan level $level referral commission"
            ]);
            return self::shareCommission($user, $formular, $amt, $plan_name, $level+1);
        }
    }
}

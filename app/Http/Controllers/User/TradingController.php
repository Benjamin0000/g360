<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\G360;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\TradePkg;
use App\Models\Trading;
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
        if($package = TradePkg::find()){
            $amt = $package->amount;
            if($user->self::$trx_balance >= $amt){
                $user->self::$trx_balance -= $amt;
                $user->save();
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$amt,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$trx_balance,
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
        $total = $trades->count();
        return view('user.trading.history', compact('trades', 'total'));
    }
}

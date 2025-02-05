<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\SuperAssociate;
use App\Models\User;
use App\Models\Loan;
use App\Models\Lmp;
use App\Models\Rank;
use App\Models\PPP;
class DashboardController extends G360
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
        $user = Auth::user();
        $superA = SuperAssociate::where('user_id', $user->id)->exists();
        if(!$superA){
            SuperAssociate::create([
                'id'=>Helpers::genTableId(SuperAssociate::class),
                'user_id'=>$user->id
            ]);
        }
        $histories = WalletHistory::where([ 
            ['user_id', $user->id]
        ])->latest()->take(20)->get();
        return view('user.dashboard.index',  compact('histories'));
    }
    /**
     * Reactivate Supper assoc
     *
     * @return \Illuminate\Http\Response
     */
    public function reactivateSuperAssoc(Request $request)
    {
        $user = Auth::user();
        $sA = SuperAssociate::where('user_id', $user->id)->first();
        $rank = Rank::find(1);
        $fee = $rank->fee;
        $super_pkg_id = 4;
        switch($request->type){
            case 'ac': 
                if($sA && $sA->status == 1 && $user->rank_id == 0){
                    if($user->trx_balance >= $fee){
                        $user->trx_balance -= $fee;
                        $user->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$fee,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$trx_balance,
                            'type'=>'debit',
                            'description'=>self::$cur.$fee.' debited for super Associate bonus reactivation'
                        ]);
                        $sA->status = 0;
                        $sA->grace += 1;
                        $sA->last_grace = Carbon::now();
                        $sA->save();
                        return redirect(route('user.dashboard.index'))
                        ->with('success', 'Super associate bonus has been reactivated for another 30 days');
                    }else{
                        return redirect(route('user.dashboard.index'))
                        ->with('error', 'Insufficient fund in your Transaction wallet');
                    }
                }
                break;
            case 'cl': 
                if($sA && $sA->status == 2 && $user->rank_id == 0){
                    if($user->pkg_id < $super_pkg_id){
                        return back()->with('error', 'You must be in the Super package to claim this prize');
                    }
                    $exp_months = $rank->lmp_months;
                    $lmp_amt = $rank->total_lmp/$exp_months;
                    if($request->tp == 'm'){
                        #issue monthly payment
                        Lmp::create([
                            'id'=>Helpers::genTableId(Lmp::class),
                            'name'=>'Super associate',
                            'user_id'=>$user->id,
                            'gnumber'=>$user->gnumber,
                            'rank_id'=>$rank->id,
                            'amount'=>$lmp_amt,
                            'total_times'=>$exp_months
                        ]);
                        $amount = $rank->loan - $rank->total_lmp;
                        $user->loan_elig_balance+=$amount; 
                        $msg = 'Leadership monthly bonus activated';
                    }elseif($request->tp == 'l'){
                        #issue no interest loan
                        $exp_days = $rank->loan_exp_m*self::$month_end;
                        $amount = $rank->loan;
                        Loan::create([
                            'id'=>Helpers::genTableId(Loan::class),
                            'user_id'=>$user->id,
                            'gnumber'=>$user->gnumber,
                            'amount'=>$amount,
                            'total_return'=>$amount,
                            'interest'=>10,
                            'exp_months'=>$rank->loan_exp_m,
                            'grace_months'=>3,
                            'extra'=>'Super associate Loan',
                            'expiry_date'=>Carbon::now()->addDays($exp_days)
                        ]);
                        $user->with_balance += $amount;
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$amount,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$with_balance,
                            'type'=>'credit',
                            'description'=>self::$cur.$amount.
                            ' '.$rank->name.' Loan'
                        ]);
                        $msg = 'Loan activated';
                    }else{
                        return redirect(route('user.dashboard.index'))
                        ->with('error', 'You don\'t have permission to access that page');                        
                    }
                    if($sA->grace > 0)
                        $rank->prize = (30/100)*$rank->prize;
                    $user->with_balance += $rank->prize;
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$rank->prize,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$with_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.$rank->prize.
                        ' earned from '.$rank->name.' reward'
                    ]);
                    $sA->status = 3; #claimed
                    $sA->save();
                    $user->rank_id = $rank->id;
                    $user->save();
                    return redirect(route('user.dashboard.index'))
                            ->with('success', $msg);
                }
                break;
            default: 
                return redirect(route('user.dashboard.index'))
                ->with('error', 'You don\'t have permission to access that page');
        }
        return redirect(route('user.dashboard.index'))
        ->with('error', 'You don\'t have permission to access that page');
    }
    /**
     * Reactivate Supper assoc
     *
     * @return \Illuminate\Http\Response
    */
    public function reactivatePPP()
    {
        $user = Auth::user();
        $ppp = $user->ppp;
        $fee = Helpers::getRegData('ppp_r_fee');
        if($ppp->status == 2){
            if($user->trx_balance >= $fee){
                $user->trx_balance -= $fee;
                $user->save();
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$fee,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$trx_balance,
                    'type'=>'debit',
                    'description'=>self::$cur.$fee.' debited for personal performance point reactivation'
                ]);
                $ppp->status = 0;
                $ppp->grace += 1;
                $ppp->graced_at = Carbon::now();
                $ppp->save();
                return back()->with('success', 'Personal performance point reactivated for another 30days');
            }else{
                return back()->with('error', "Insufficient fund in your TRX-Wallet");
            }
        }else{
            return back()->with('error', "Sorry you can't access that resource");
        }
    }
}

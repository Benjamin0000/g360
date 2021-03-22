<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\SuperAssociate;
use App\Models\User;
use App\Models\Loan;
use App\Models\Lmp;
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
        $histories = WalletHistory::where([ 
            ['user_id', $user->id],
            ['name', '<>', 'h_token'],
            ['name', '<>', 'cpv'],
            ['name', '<>', 'award_point']
        ])
        ->latest()->take(15)->get();
        $referals = [];
        $d_referals = User::where('ref_gnum', $user->gnumber)->latest()->get();
        if($d_referals->count()){
            foreach($d_referals as $d_referal){
                array_push($referals, $d_referal);
                self::getRef($d_referal->gnumber, $referals);
            }
        }
        return view('user.dashboard.index',  compact('histories', 'referals'));
    }
     /**
     * Get referral levels
     *
     * @return \Illuminate\Http\Response
     */   
    private static function getRef($gnumber, &$referals, $level=1)
    {
        if($level > 15 || count($referals) >= 15) return;
        $user = User::where('ref_gnum', $gnumber)->first();
        if($user){
            array_push($referals, $user);
            self::getRef($user->gnumber, $referals, $level+1);
        }
    }
    /**
     * Reactivate Supper assoc
     *
     * @return \Illuminate\Http\Response
     */
    public function reactivateSuperAssoc(Request $request)
    {
        $user = Auth::user();
        $fee = 5000;
        $sA = SuperAssociate::where('user_id', $user->id)->first();
        switch($request->type){
            case 'ac': 
                if($sA && $sA->status == 1){
                    if($user->self::$trx_balance >= $fee){
                        $user->self::$trx_balance -= $fee;
                        $user->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$fee,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$trx_balance,
                            'type'=>'debit',
                            'description'=>$cur.$fee.' debited for super Associate bonus reactivation'
                        ]);
                        $sA->status = 0;
                        $sA->grace += 1;
                        $sA->last_grace = Carbon::now();
                        $sA->save();
                        return redirect('user.dashboard.index')
                        ->with('success', 'Super associate bonus has been reactivated for another 30 days');
                    }else{
                        return redirect('user.dashboard.index')
                        ->with('error', 'Insufficient fund in your Transaction wallet');
                    }
                }
                break;
            case 'cl': 
                if($sA && $sA->status == 2){
                    $exp_months = 6;
                    if($request->tp == 'm'){
                        #issue monthly payment
                        Lmp::create([
                            'id'=>Helpers::genTableId(Lmp::class),
                            'name'=>'Super associate Loan',
                            'user_id'=>$user->id,
                            'gnumber'=>$user->gnumber,
                            'rank_id'=>1,
                            'amount'=>10000,
                            'total_times'=>$exp_months
                        ]);
                        $amount = 100000-10000;
                        $user->self::$loan_elig_balance+=$amount; 
                        $msg = 'Leadership monthly bonus activated';
                    }elseif($request->tp == 'l'){
                        #issue no interest loan
                        $exp_days = $exp_months*self::$month_end;
                        $amount = 100000;
                        Loan::create([
                            'id'=>Helpers::genTableId(Loan::class),
                            'user_id'=>$user->id,
                            'gnumber'=>$user->gnumber,
                            'amount'=>$amount,
                            'total_return'=>$amount,
                            'interest'=>10,
                            'exp_months'=>$exp_months,
                            'grace_months'=>3,
                            'extra'=>'Super associate Loan',
                            'expiry_date'=>Carbon::now()->addDays($exp_days)
                        ]);
                        $msg = 'Loan activated';
                    }else{
                        return redirect('user.dashboard.index')
                        ->with('error', 'You don\'t have permission to access that page');                        
                    }
                    $sA->status = 3; #claimed
                    $sA->save();
                    $user->rank_id = 1;
                    $user->save();
                    return redirect('user.dashboard.index')
                            ->with('success', $msg);
                }
                break;
            default: 
                return redirect('user.dashboard.index')
                ->with('error', 'You don\'t have permission to access that page');
        }
        return redirect('user.dashboard.index')
        ->with('error', 'You don\'t have permission to access that page');
    }

  
}

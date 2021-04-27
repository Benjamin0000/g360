<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\Reward;
use App\Models\Lmp;
use App\Models\Loan;
class RewardController extends G360
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
        $new_rewards = Reward::where([
            ['user_id', Auth::id()],
            ['status', 0] 
         ])->get();

        $rewards = Reward::where([
            ['user_id', Auth::id()]
        ])->latest()->paginate(10);
        return view('user.reward.index', compact('new_rewards', 'rewards'));
    }
    /**
     * Select leadership monthly bonus
     * @param $id Reward id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function selectLoan(Request $request, $id)
    {
        $user = Auth::user();
        $month_end = self::$month_end;
        $reward = Reward::where([
            ['id', $id],
            ['user_id', $user->id],
            ['status', 0]
        ])->first();
        if(!$reward){
            return redirect(route('user.dashboard.index'))
            ->with('error', 'Sorry you can\'t access that page');
        }
        $onLoan = Loan::where([ 
            ['user_id', $user->id], 
            ['status', 0]  
        ])->exists();
        if($onLoan){
            return redirect(route('user.reward.index'))
            ->with('error', 'You can\'t access a loan when you are already on a loan');
        }
        $reward->selected = 'loan';
        $reward->status = 1;
        $reward->save();
        #give loan
        $exp_days = $reward->loan_month*$month_end;
        $total_return = $reward->loan_amount + (($reward->loan_interest/100)*$reward->loan_amount);
        Loan::create([
            'id'=>Helpers::genTableId(Loan::class),
            'user_id'=>$user->id,
            'gnumber'=>$user->gnumber,
            'amount'=>$reward->loan_amount,
            'total_return'=>$total_return,
            'interest'=>$reward->loan_interest,
            'grace_interest'=>$reward->loan_grace_interest,
            'exp_months'=>$reward->loan_month,
            'grace_months'=>$reward->loan_grace_month,
            'extra'=>$reward->name.' Loan',
            'expiry_date'=>Carbon::now()->addDays($exp_days)
        ]);
        $user->with_balance += $reward->loan_amount;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$reward->loan_amount,
            'gnumber'=>$user->gnumber,
            'name'=>'with_balance',
            'type'=>'credit',
            'description'=>self::$cur.number_format($reward->loan_amount).
            ' '.$reward->name.' Loan'
        ]);
        return redirect(route('user.reward.index'))
        ->with('success', 'Loan has been activated');
    }
    /**
     * Select leadership monthly bonus
     * @param $id Reward id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function selectLmp(Request $request, $id)
    {
        $user = Auth::user();
        $reward = Reward::where([
            ['id', $id],
            ['user_id', $user->id],
            ['status', 0]
        ])->first();
        if(!$reward){
            return redirect(route('user.dashboard.index'))
            ->with('error', 'Sorry you can\'t access that page');
        }
        Lmp::create([
            'id'=>Helpers::genTableId(Lmp::class),
            'name'=>$reward->name,
            'user_id'=>$user->id,
            'gnumber'=>$user->gnumber,
            'rank_id'=>$reward->rank_id,
            'amount'=>($reward->lmp_amount/$reward->lmp_month),
            'total_times'=>$reward->lmp_month
        ]);
        $reward->selected = 'lmp';
        $reward->status = 1;
        $reward->save();
        #store loan amount in loan eligibility
        $amount = $reward->loan_amount-$reward->lmp_amount;
        $user->loan_elig_balance+=$amount;
        $user->save();
        return redirect(route('user.reward.index'))
        ->with('success', 'Leadership monthly bonus has been activated');
    }
}

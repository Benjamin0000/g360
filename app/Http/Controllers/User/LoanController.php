<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use App\Http\Controllers\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use App\Models\WalletHistory;
use App\Models\LoanSetting;
use App\Http\Helpers;
class LoanController extends G360
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
        $loans = Loan::where('user_id', $user->id)->latest()->paginate(15);
        #loan request aproval
        $loanRequests = Loan::where([
            ['garant', $user->id],
            ['status', 0],
            ['g_approve', 0]
        ])->get();
        $active_loan = Loan::where([
            ['user_id', $user->id],
            ['status', 0],
        ])->whereNotNull('expiry_date')->first();
        return view('user.loan.index', 
        compact('loans', 'loanRequests', 'active_loan'));
    } 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apply()
    {
        return view('user.loan.apply');
    }
    /**
     *Request Loan 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function requestLoan(Request $request)
    {
        $settings = LoanSetting::orderBy('min', 'ASC')->get();
        if(!$settings->count())
            return back()->with('error', "Loan request not available at this time");
        $this->validate($request, [
            'amount'=>['required', 'numeric'],
            'range'=>['required', 'numeric']
        ]);
        $user = Auth::user();
        $garant = $request->gno_1;
        $amount = $request->amount;
        $period = $request->range;

        $min = LoanSetting::orderBy('min', 'ASC')->first()->min;
        $max = LoanSetting::orderBy('max', 'DESC')->first()->max;

        if($amount < $min)
            return back()->with('error', 'Minimum loan amount is '.self::$cur.$min);
        if($amount > $max)
            return back()->with('error', 'Maximum loan amount is '.self::$cur.$max);
          
        foreach($settings as $setting){
            if($amount >= $setting->min && $amount <= $setting->max){
                $month = $setting->exp_months;
                $grace_month = $setting->grace_months;
                $interest = $setting->interest;
                $grace_interest = $setting->f_interest;
                break;
            }
        }
        if($period != $month)
            return back()->with('error', 'Invalid range chosen for this loan');

        $loanDebt = abs($user->loanBalance());
        if($loanDebt > 0){
            return back()->with('error', 'You have a debt of '.
            self::$cur.number_format($loanDebt).' pay up then request for another loan');
        }
        if($user->loanExists()){
            return back()->with('error', 'You already have a pending loan 
            awaiting approval from your guarantor');
        }
        $total_return = (($interest/100)*$amount) + $amount;
        if($request->nn == 1){
            if($user->loan_elig_balance >= $amount){
                $user->loan_elig_balance -= $amount;
                $user->with_balance += $amount;
                $user->save();
                Loan::create([
                    'id'=>Helpers::genTableId(Loan::class),
                    'user_id'=>$user->id,
                    'gnumber'=>$user->gnumber,
                    'amount'=>$amount,
                    'total_return'=>$total_return,
                    'interest'=>$interest,
                    'grace_interest'=>$grace_interest,
                    'exp_months'=>$month,
                    'grace_months'=>$grace_month,
                    'extra'=>$request->extra,
                ]);
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$amount,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$with_balance,
                    'type'=>'credit',
                    'description'=>self::$cur.$amount.
                    ' loan'
                ]);
                return back()
                ->with('success', 'Congrates! your loan has been credited to you.');
            }else{
                return back()
                ->with('error', "You can't access a loan of this amount");
            }
        }else{    
            $garantor = User::where('gnumber', $garant)->first();
            if($garantor){
                if($garantor->loanExists()){
                    return back()
                    ->with('error', 'Your guarantor is on a loan which he is yet to pay');
                }
                if($garantor->loan_elig_balance >= $amount){
                    $garantor->loan_elig_balance -= $amount;
                    $garantor->save();
                    Loan::create([
                        'id'=>Helpers::genTableId(Loan::class),
                        'garant'=>$garantor->id,
                        'user_id'=>$user->id,
                        'gnumber'=>$user->gnumber,
                        'amount'=>$amount,
                        'total_return'=>$total_return,
                        'garant_amt'=>$amount,
                        'interest'=>$interest,
                        'grace_interest'=>$grace_interest,
                        'exp_months'=>$month,
                        'grace_months'=>$grace_month,
                        'extra'=>$request->extra,
                    ]);
                    return back()
                    ->with('success', 'Loan has been submited and awaiting the approval of your guarantor');
                }else{
                    return back()
                    ->with('error', 'Your Guarantor cannot grant you this loan');
                }
            }else{
                return back()
                ->with('error', 'Your Guarantor is not in our system, are you sure the Gnumber is correct?');
            }
        }
    }
    /**
     *Guarantors loan approval 
     *
     * @param  loan_id $id
     * @return \Illuminate\Http\Response
     */
    public function loanApprove($id)
    {
        $user = Auth::user();
        $loan = Loan::where([
            ['id', $id],
            ['garant', $user->id],
            ['status', 0],
            ['g_approve', 0]
        ])->first();
        $exp_days = $loan->exp_months*self::$month_end;
        if($loan){
            switch($request->type){
                case 'approve': 
                    if($beneficiary = $loan->user){
                        $beneficiary->with_balance += $loan->amount;
                        $beneficiary->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$beneficiary->id,
                            'amount'=>$loan->amount,
                            'gnumber'=>$beneficiary->gnumber,
                            'name'=>'with_balance',
                            'type'=>'credit',
                            'description'=>self::$cur.number_format($loan->amount).
                            ' Loan'
                        ]);
                        $loan->g_approve = 1;
                        $loan->expiry_date = Carbon::now()->addDays($exp_days);
                        $loan->save();
                        return back()->with('success', "Loan approved");
                    }else{
                        return back()->with('error', 'Loan beneficiary not found');
                    }
                case 'disapprove':
                    $loan->g_approve = 2;
                    $loan->expiry_date = "";
                    $loan->save();
                    $user->loan_elig_balance += $loan->amount;
                    $user->save();
                    return back()->with('success', 'Loan has been disapproved');
                default: 
                    return back()->with('error', 'Invalid operation');
            }
        }else{
            return back()->with('error', 'Loan not found');
        }
    }
    public function promptLoanPayment()
    {
        $user = Auth::user();
        $loan = Loan::where([
            ['user_id', $user->id],
            ['status', 0],
        ])->whereNotNull('expiry_date')->first();
        return view('user.loan.debt_pay', compact('loan'));
    }
    /**
     *pay loan 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {
        $user = Auth::user();
        $loan = Loan::where([
            ['user_id', $user->id],
            ['status', 0]
        ])->whereNotNull('expiry_date')->first();
        if($loan){
            $left_amt = $loan->total_return - $loan->returned;
            if($user->trx_balance >= $left_amt){
                $user->trx_balance -= $left_amt;
                $user->save();
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$left_amt,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$trx_balance,
                    'type'=>'debit',
                    'description'=>self::$cur.$left_amt.
                    ' loan payment'
                ]);
                $loan->returned += $left_amt;
                $loan->status = 1;
                $loan->save();
                Task::creditGurantors($loan);
                return redirect(route('user.loan.index'))->with('success', 'your loan has been cleared');
            }else{
                return back()
                ->with('error', 'Insufficent fund in your TRX-wallet to clear your loan');
            }
        }
        return back();
    }
    /**
     *Extend loan with interest
     *
     * @param  loan_id $id
     * @return \Illuminate\Http\Response
     */
    public function loanExtend($id)
    {
        $user = Auth::user();
        $loan = Loan::where([
            ['id', $id],
            ['user_id', $user->id],
            ['status', 0],
        ])->whereNotNull('expiry_date')->first();
        if($loan){
            if($loan->defaulted > 0)
                return back()->with('error', 'Cannot extend this loan');
            
            $debt = $loan->total_return - $loan->returned;
            $amount = ($loan->grace_interest / 100) * $debt;
            $exp_days = $loan->grace_months*self::$month_end;
            $loan->grace_date = Carbon::now()->addDays($exp_days);
            $loan->total_return += $amount;
            $loan->defaulted = 1;
            $loan->defaulted_at = Carbon::now();
            $loan->save();
            return back()->with('success', 'Loan has been extended');
        }else{
            return back()->with('error', 'Loan not found');
        }
    }
}

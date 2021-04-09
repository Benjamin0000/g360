<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\User\TradingController as TradeCon;
use App\Models\TrackFreeUser;
use App\Models\WalletHistory;
use App\Models\SuperAssociate;
use App\Models\User;
use App\Models\Package;
use App\Models\Lmp;
use App\Models\Rank;
use App\Models\Reward;
use App\Models\Loan;
use App\Models\GsClub;
use App\Models\GsClubH;
use App\Models\PPP;
use App\Models\Trading;
use App\Models\PartnerProfit;
use App\Models\PContract;
use App\Models\GTR;
use App\Models\Agent;
use App\Models\AgentSetting;
use App\Http\Helpers;
use Carbon\Carbon;
class Task extends G360
{
    /**
     * Track free users;
     *
     * @return Void
    */
    public static function trackFreeUsers()
    {
        $trial_period = 30; // days
        $percent = 20; // percent increase
        $free_pkg_id = 1;
        $fUsers = TrackFreeUser::where('status', 0);
        if($fUsers->exists()){
            $fUsers = $fUsers->get();
            foreach($fUsers as $fUser){
                if($fUser->user->pkg_id == $free_pkg_id && $fUser->created_at->diffInDays() >= $trial_period){
                    if($user = User::find($fUser->user_id)){
                        $user->free_t_fee = $percent;
                        $user->save();
                        $fUser->status = 1;
                        $fUser->save();
                    }else
                        $fUser->delete();
                }
            }
        }
    }
    /**
     * Share pending wallet
     *
     * @return Void
    */
    public static function sharePendingWallet()
    {
        $last_pkg_id = 7;
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $total = $user->self::$pend_balance;
                $billForLoan = false;
                $billForPkg = false;
                $type = '';
                if($total >= 100){
                    if($user->pkg_id != $last_pkg_id)
                        $billForPkg = true;
                    if($user->haveUnPaidLoan())
                        $billForLoan = true;
                    if($billForLoan && $billForPkg){
                        $formular = [20, 5, 15, 15, 45];
                    }elseif($billForPkg || $billForLoan){
                        if($billForLoan)
                            $type = 'loan';
                        else
                            $type = 'pkg';
                        $formular = [20, 5, 30, 45];
                    }else{
                        $formular = [20, 5,  0, 75];
                    }
                    self::pSharing($user, $formular, $type);
                }
            }
        }
    }
    public static function pSharing(User $user, $formular, $type = '')
    {
        $total = $user->self::$pend_balance;
        $pend_trx = $user->self::$pend_trx_balance;
        $pkg_bal = 0;
        $trx_bal = 0;
        $w_bal = 0;
        $award_pt = 0;
        $loan_bal = 0;
        $excess = 0;
        if(count($formular) == 5){
            $pkg_bal = ($formular[2] / 100) * $total;
            $loan_bal = ($formular[3] / 100) * $total;
            $trx_bal = ($formular[4] / 100) * $total;
        }else{
            $trx_bal = ($formular[3] / 100) * $total;
            if($perc = $formular[2]){
                if($type == 'loan')
                    $loan_bal = ($perc / 100) * $total;
                elseif($type == 'pkg')
                    $pkg_bal = ($perc / 100) * $total;
            }
        }
        $award_pt = ($formular[0] / 100) * $total;
        $w_bal = ($formular[1] / 100) * $total;
        if($loan_bal){
            $loan = Loan::where([
                ['user_id', $user->id],
                ['status', 0]
            ])->first();
            if($loan){
                $loan->returned += $loan_bal;
                if($loan->returned >= $loan->total_return){
                    $loan->status = 1;
                    if($loan->returned > $loan->total_return){
                        $excess = $loan->returned - $loan->total_return;
                        $loan->returned = $loan->total_return;
                    }
                    self::creditGurantors($loan);
                }
                $loan->save();
            }
        }
        $trx_amount = $trx_bal + $excess + $pend_trx;
        if($pkg_bal)
            $user->self::$pkg_balance += $pkg_bal;
        $user->self::$award_point += $award_pt;
        $user->self::$with_balance += $w_bal;
        $user->self::$trx_balance += $trx_amount;
        $user->self::$pend_balance = 0;
        $user->self::$pend_trx_balance = 0;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$trx_amount,
            'gnumber'=>$user->gnumber,
            'name'=>self::$trx_balance,
            'type'=>'credit',
            'description'=>self::$cur.number_format($trx_amount).' daily earning'
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$award_pt,
            'gnumber'=>$user->gnumber,
            'name'=>self::$award_point,
            'type'=>'credit',
            'description'=>$award_pt.' award point from daily earning'
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$w_bal,
            'gnumber'=>$user->gnumber,
            'name'=>self::$with_balance,
            'type'=>'credit',
            'description'=>self::$cur.number_format($w_bal).' daily earning'
        ]);
        if($pkg_bal){
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$pkg_bal,
                'gnumber'=>$user->gnumber,
                'name'=>self::$pkg_balance,
                'type'=>'credit',
                'description'=>self::$cur.number_format($pkg_bal).' daily earning'
            ]);
        }
    }
    /**
     * auto upgrade users plan
     *
     * @return Void
    */
    public static function autoUpgrade()
    {
        $last_pkg_id = 7;
        $fee = 0;
        $users = User::where([
            ['status', 1],
            ['pkg_id', '<' , $last_pkg_id]
        ]);
        if($users->exists()){
            $users = $users->get();
            foreach($users as $user){
                if($current_pkg = Package::find($user->pkg_id)){
                    if($nxt_package = Package::find($user->pkg_id + 1)){
                        $amount = $nxt_package->amount - $current_pkg->amount;
                        if($percent = $user->free_t_fee)
                            $fee = ($percent/100)*$amount;
                        if($user->self::$pkg_balance >= $amount){
                            if($fee){
                                $total = $amount+$fee;
                                if($user->self::$pkg_balance < $total){
                                    if($user->self::$trx_balance < $fee)
                                       return;
                                    else{
                                        $user->self::$trx_balance-=$fee;
                                        $user->save();
                                        WalletHistory::create([
                                            'id'=>Helpers::genTableId(WalletHistory::class),
                                            'user_id'=>$user->id,
                                            'amount'=>$fee,
                                            'gnumber'=>$user->gnumber,
                                            'name'=>self::$trx_balance,
                                            'type'=>'debit',
                                            'description'=>self::$cur.$fee.' Debited for '.ucfirst($nxt_package->name).' package '.$percent.'% fee'
                                        ]);
                                    }
                                }else{
                                    $user->self::$pkg_balance-=$fee;
                                    $user->save();
                                    WalletHistory::create([
                                        'id'=>Helpers::genTableId(WalletHistory::class),
                                        'user_id'=>$user->id,
                                        'amount'=>$fee,
                                        'gnumber'=>$user->gnumber,
                                        'name'=>self::$pkg_balance,
                                        'type'=>'debit',
                                        'description'=>self::$cur.$fee.' Debited for '.ucfirst($nxt_package->name).' package '.$percent.'% fee'
                                    ]);
                                }
                            }
                            $user->free_t_fee = 0; #clear fee
                            $user->self::$pkg_balance-=$amount;
                            $user->save();
                            WalletHistory::create([
                                'id'=>Helpers::genTableId(WalletHistory::class),
                                'user_id'=>$user->id,
                                'amount'=>$amount,
                                'gnumber'=>$user->gnumber,
                                'name'=>self::$pkg_balance,
                                'type'=>'debit',
                                'description'=>self::$cur.$amount.' Debited for '.ucfirst($nxt_package->name).' package'
                            ]);
                            $nxt_package->activate($user, 'none', 'PKG-Wallet auto upgrade');
                        }
                    }
                }
            }
        }
    }
     /**
     * Super assoc. welcome reward
     *
     * @return void
    */
    public static function superAssocReward()
    {
        $rank = Rank::find(1);
        $pv = $rank->pv;
        $minutes = $rank->minutes;
        $graced_minutes = $rank->graced_minutes;
        $super_pkg_id = 4;
        $sAs = SuperAssociate::where('status', 0)->get();
        foreach($sAs as $sA){
            if($sA->created_at->diffInMinutes() <= $minutes){
                #allow flow
            }else{
                if($sA->last_grace != '' && Carbon::parse($sA->last_grace)->diffInMinutes() <= $graced_minutes){
                    #allow flow
                }else{
                    $sA->status = 1; #faild
                    $sA->save();
                    return; #disallow flow
                }
            }
            if($user = User::find($sA->user_id)){
                if($user->cpv >= $pv && $user->pkg_id >= $super_pkg_id){
                    if(Helpers::checkLegBalance($user, $pv)){
                        $sA->status = 2; #made it
                    }else
                        $sA->balance_leg = 1; #balance leg
                    $sA->save();
                }
            }else{
                $sA->delete();
            }
        }
    }
     /**
     * Users Ranking
     *
     * @return void
    */
    public static function ranking()
    {
        $ranks = Rank::all();
        $last_rank = Rank::orderBy('id', 'DESC')->first()->id;
        $no_rank = 0;
        foreach($ranks as $rank){
            $users = User::where([
                ['rank_id', '<>', $last_rank],
                ['rank_id', '<>', $no_rank]
            ])->get();
            foreach($users as $user){
                $cpv = $user->cpv;
                if($rank->id != $last_rank){
                    if($cpv >= $rank->pv && $cpv < $rank->next()->pv){
                        #allow the flow
                    }else{
                        return; #disallow the flow
                    }
                }else{
                    if($cpv < $rank->pv)
                        return; #disallow the flow
                }
                if(Helpers::checkLegBalance($user, $rank->pv)){
                    #credit user
                    $user->rank_id = $rank->id;
                    // $user->self::$with_balance += $rank->prize;
                    $user->self::$total_loan_balance += $user->self::$loan_elig_balance;
                    $user->self::$loan_elig_balance = 0;
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$rank->prize,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$with_balance, //self::$pend_trx_balance
                        'type'=>'credit',
                        'description'=>self::$cur.$rank->prize.
                        ' earned from '.$rank->name.' reward'
                    ]);
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$rank->prize,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$pend_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.$rank->prize.
                        ' earned from '.$rank->name.' reward'
                    ]);
                    Reward::create([
                        'id'=>Helpers::genTableId(Reward::class),
                        'name'=>$rank->name,
                        'user_id'=>$user->id,
                        'rank_id'=>$rank->id,
                        'gnumber'=>$user->gnumber,
                        'loan_amount'=>$rank->loan,
                        'loan_month'=>$rank->loan_exp_m,
                        'lmp_amount'=>$rank->total_lmp,
                        'lmp_month'=>$rank->lmp_months
                    ]);
                    $user->save();
                }
            }
        }
    }
     /**
     * Leadership monthly payment
     *
     * @return void
    */
    public static function lmp()
    {
        $lmps = Lmp::where('status', 0)->get();
        if($lmps->count()){
            foreach($lmps as $lmp){
                if($lmp->last_payed != '')
                    $last_payed = Carbon::parse($lmp->last_payed);
                else
                    $last_payed = $lmp->created_at;
                if($last_payed->diffInDays() >= self::$month_end){
                    if($user = User::find($lmp->user_id)){
                        $user->self::$pend_trx_balance += $lmp->amount;
                        $user->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$lmp->amount,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$pend_trx_balance,
                            'type'=>'credit',
                            'description'=>self::$cur.$lmp->amount.
                            ' earned from '.$lmp->name.' leadership monthly bonus'
                        ]);
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$lmp->amount,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$pend_balance,
                            'type'=>'credit',
                            'description'=>self::$cur.$lmp->amount.
                            ' earned from '.$lmp->name.' leadership monthly bonus'
                        ]);
                        $lmp->times+=1;
                        if($lmp->times >= $lmp->total_times)
                            $lmp->status = 1;
                        $lmp->last_payed = Carbon::now();
                        $lmp->save();
                    }else{
                        $lmp->delete();
                    }
                }
            }
        }
    }
    /**
     * Loan
     *
     * @return void
    */
    public static function loan()
    {
        $loans = Loan::where([ ['status', 0], ['defaulted', 0] ])->get();
        foreach($loans as $loan)
        {
            $exp_days = $loan->exp_months*self::$month_end;
            if(Carbon::parse($loan->expiry_date)->diffInDays() >= $exp_days){
                if($loan->returned >= $loan->total_return){
                    $loan->status = 1;
                    self::creditGurantors($loan);
                }else{
                    self::demandLoanDebt($loan);
                }
                $loan->save();
            }
        }
    }
    public static function demandLoanDebt(Loan &$loan)
    {
        $debt = $loan->total_return - $loan->returned;
        if($user = User::find($loan->user_id)){
            $Adebt = $debt - $user->self::$trx_balance;
            if($Adebt <= 0){
                $user->self::$trx_balance -= $debt;
                $user->save();
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$debt,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$trx_balance,
                    'type'=>'debit',
                    'description'=>self::$cur.$debt.
                    ' debited for loan payment'
                ]);
                $loan->returned += $debt;
                $debt = 0;
            }else{
                $Bdebt = $Adebt - $user->self::$with_balance;
                if($Bdebt <= 0){
                    $user->self::$with_balance -= $Adebt;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$Adebt,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$with_balance,
                        'type'=>'debit',
                        'description'=>self::$cur.$Adebt.
                        ' debited for loan payment'
                    ]);
                    $loan->returned += $Adebt;
                    $debt = 0;
                }else{
                    $Cdebt = $Bdebt - $user->self::$pend_balance;
                    if($Cdebt <= 0){
                        $user->self::$pend_balance -= $Bdebt;
                        $user->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$Bdebt,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$pend_balance,
                            'type'=>'debit',
                            'description'=>self::$cur.$Bdebt.
                            ' debited for loan payment'
                        ]);
                        $loan->returned += $Bdebt;
                        $debt = 0;
                    }else{
                        $debt = $Cdebt;
                    }
                }
            }
            if($debt){
                $loan->defaulted = 1; # defaulted
                $loan->defaulted_at = Carbon::now();
            }else{
                $loan->status = 1;
                self::creditGurantors($loan);
            }
        }else{
            $loan->delete();
        }
    }
    /**
     *Graced Loan
     *
     * @return void
    */
    public static function gracedLoan()
    {
        $loans = Loan::where([ ['status', 0], ['defaulted', 1] ])
        ->whereNull('default_date')->get();
        foreach($loans as $loan)
        {
            $exp_days = $loan->grace_months*self::$month_end;
            if(Carbon::parse($loan->grace_date)->diffInDays() >= $exp_days){
                if($loan->returned >= $loan->total_return){
                    $loan->status = 1;
                    self::creditGurantors($loan);
                }else{
                    #bill him and gurantor
                }
                $loan->save();
            }
        }
    }
    public static function creditGurantors(Loan $loan)
    {
        if($loan->garant){
            $garant = User::find($loan->garant);
            if($garant){
                $garant->self::$loan_elig_balance += $loan->garant_amt;
                $garant->save();
            }
        }else{
            #a ranked user
            if($user = User::find($loan->user_id)){
                $user->self::$loan_elig_balance += $loan->amount;
                $user->save();
            }
        }
    }
    /**
     *Process GsClub transactions
     *
     * @return void
    */
    public static function gsClub()
    {
       $givers = GsClub::where([
          ['status', 0],
          ['g', 1]
       ])->orderBy('created_at', 'ASC')->get();
       if($givers->count()){
            foreach ($givers as $giver) {
                if($gtr = GTR::where('amount', $giver->gbal)->first()){
                    if(Carbon::parse($giver->lastg)->diffInMinutes() >= $gtr->g_hours)
                        self::gsclubR($giver, $gtr);
                }
            }
        }
    }
    /**
     *Get GsClub receiver
     *
     * @return void
    */
    private static function gsclubR(GsClub $giver, GTR $gtr, $r_id = 0)
    {
        $first_amt = GTR::orderBy('id', 'ASC')->first()->amount;
        $last = GTR::orderBy('id', 'DESC')->first()->id;
        $r_count = $gtr->r_count;
        $pay_back = $gtr->pay_back;
        $days = $gtr->r_days;
        $dateCheck = Carbon::now()->subMinutes($days);
        $receiver = GsClub::where([
            ['id', '<>', $r_id],
            ['status', 0],
            ['g', 0],
            ['gbal', $giver->gbal]
        ])->where('lastr', '<=', $dateCheck)
        ->orderBy('created_at', 'asc')->first();
        if($receiver){
            $receiver->r_count+=1;
            if($receiver->r_count >= $r_count){
                $total = $giver->gbal * $r_count;
                $receiver->wbal += $total - $pay_back;
                $receiver->gbal = $pay_back;
                $receiver->r_count = 0;
                #convert receiver to a giver
                $receiver->g = 1;
                $receiver->lastg = Carbon::now();
                if($gtr->id == $last){
                    $receiver->gbal = $first_amt;
                    $receiver->circle += 1;
                    $receiver->status = 1;
                }
            }
            $notEligible = false;
            if(!$receiver->user->validPartner()){
                if($receiver->user->totalValidRef() < $gtr->total_ref)
                    $notEligible = true;
            }else{
                // $receiver->status = 0;
            }
            if($receiver->agent){
                #requirement
            }
            if($notEligible){
                self::gsclubR($giver, $gtr, $receiver->id);
            }else{
                $receiver->save();
                #convert giver to a receiver
                $giver->g = 0;
                $giver->lastr = Carbon::now();
                $giver->save();
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$receiver->user_id,
                    'amount'=>$giver->gbal,
                    'type'=>0,
                    'description'=>self::$cur.number_format($giver->gbal)." Received from ".
                    $giver->user->fname.' '.$giver->user->lname
                ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$giver->user_id,
                    'amount'=>$giver->gbal,
                    'type'=>1,
                    'description'=>self::$cur.number_format($giver->gbal)." Sent to ".
                    $receiver->user->fname.' '.$receiver->user->lname
                ]);
            }
        }
    }
    /**
     * Personal Perfomance point
     *
     * @return void
    */
    public static  function ppp()
    {
        $acheived = 1;
        $needGrace = 2;
        $faild = 3;
        $reward = Helpers::getRegData('ppp_reward_amount');
        $required_referrals = Helpers::getRegData('ppp_total_referrals');
        $required_minutes = Helpers::getRegData('ppp_minutes');
        $graced_minutes = Helpers::getRegData('ppp_grace_minutes');
        $grace_trail = Helpers::getRegData('ppp_grace_trail');
        $ppps = PPP::where('status', 0);
        if($ppps->exists()){
            foreach($ppps as $ppp){
                if($ppp->created_at->diffInMinutes() >= $required_minutes){
                    if($user = User::find($ppp->user_id)){
                        if($user->totalValidRef() >= $required_referrals){
                            if($ppp->grace == 0){
                                $user->self::$pend_balance += $reward;
                                $user->save();
                                WalletHistory::create([
                                    'id'=>Helpers::genTableId(WalletHistory::class),
                                    'user_id'=>$user->id,
                                    'amount'=>$reward,
                                    'gnumber'=>$user->gnumber,
                                    'name'=>self::$pend_balance,
                                    'type'=>'credit',
                                    'description'=>self::$cur.$reward.
                                    ' earned from personal performance Reward'
                                ]);
                            }
                            $ppp->status = $acheived;
                            $ppp->save();
                        }else{
                            if($ppp->graced_at != ''){
                                if(Carbon::parse($ppp->graced_at)->diffInMinutes() >= $graced_minutes){
                                    $ppp->grace += 1;
                                    if($ppp->grace >= $grace_trail)
                                        $ppp->status = $faild;
                                    else
                                        $ppp->status = $needGrace;
                                    $ppp->save();
                                }
                            }else{
                                $ppp->status = $needGrace;
                                $ppp->save();
                            }
                        }
                    }else{
                        $ppp->delete();
                    }
                }
            }
        }
    }
    /**
     * Reward Personal Perfomance point
     *
     * @return void
    */
    public static function rPPP()
    {
        $acheived = 1;
        $pv = Helpers::getRegData('ppp_pv');
        $reward = Helpers::getRegData('ppp_payment');
        $ppps = PPP::where('status', $acheived);
        if($ppps->exists()){
            foreach($ppps->get() as $ppp){
                if($user = User::find($ppp->user_id)){
                    $value = intval($user->cpv/$pv);
                    $value -= $ppp->point;
                    if($value > 0){
                        $amt = $reward * $value;
                        $user->self::$pend_balance += $amt;
                        $user->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$amt,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$pend_balance,
                            'type'=>'credit',
                            'description'=>self::$cur.$amt.
                            ' earned from personal performance point'
                        ]);
                        $ppp->point += $value;
                        $ppp->save();
                    }
                }
            }
        }
    }
     /**
     *For traders
     *
     * @return void
    */
    public static function trading()
    {
        $trading = Trading::where('status', 0)->get();
        foreach($trading as $trade){
            if($trade->created_at->diffInHours() >= 48 && $trade->interest_returned == 0){
                if($user = User::find($trade->user_id)){
                    $int_amt = $trade->interest_amt;
                    $user->self::$with_balance += $int_amt;
                    $trade->interest_returned = 1;
                    $trade->save();
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$int_amt,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$with_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.$int_amt.
                        " Interest from $trade->name plan trading"
                    ]);
                    #pay referrals
                    TradeCon::sharePv($user, $trade->pv, $level=1);
                    if($trade->ref_percent != ''){
                        $formular =  explode(',', $trade->ref_percent);
                        TradeCon::shareCommission($user, $formular, $trade->amount, $trade->name, $level=1);
                    }
                }else{
                    $trade->delete();
                    continue;
                }
            }
            if($trade->created_at->diffInDays() < $trade->exp_days || $trade->returned < $trade->amount){
                if(Carbon::parse($trade->last_added)->diffInHours() >= 24 && $trade->returned < $trade->amount){
                    $trade->returned += $trade->amt_return;
                    $trade->last_added = Carbon::now();
                    $trade->save();
                }
            }else{
                $returned = $trade->returned;
                $user->self::$with_balance += $returned;
                $user->save();
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$returned,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$with_balance,
                    'type'=>'credit',
                    'description'=>self::$cur.$returned.
                    " Your Capital from $trade->name plan trading"
                ]);
                $trade->status = 1;
                $trade->save();
            }
        }
    }
     /**
     *Get profit share statictics and share
     *
     * @return void
    */
    public static function shareSignupProfit()
    {
        $track = PartnerProfit::where('name', 'partner')->first();
        $contracts = PContract::where('status', 0);
        $total = $track->users_count - $track->last_count;
        if($total > 0){
            if($contracts->exists()){
                foreach($contracts->get() as $contract){
                    $partner = $contract->partner;
                    if($contract->returned >= $contract->total_return && $partner->type == 0){
                        $contract->status = 1;
                    }else{
                        $amt = ($partner->s_credit*$total);
                        $contract->creditContract($amt);
                    }
                    $contract->save();
                }
                self::expire_contract();
            }
            $track->last_count += $total;
            $track->save();
        }
    }
    public static function expire_contract()
    {
        $contracts = PContract::where('status', 0);
        if($contracts->exists()){
            foreach($contracts->get() as $contract){
                $partner = $contract->partner;
                if($contract->created_at->diffInMonths() >= $contract->months && $partner->type == 0){
                    $contract->status = 2;
                    $contract->save();
                }elseif($contract->returned >= $contract->total_return && $partner->type == 0){
                    $contract->status = 1;
                    $contract->save();
                }
            }
        }
    }
    public static function sAgentRGcoin()
    {
        $sAgents = Agent::where('type', 1)->get();
        if($sAgents->count()){
            $set = AgentSetting::first();
            if($set){
                foreach($sAgents as $sAgent){
                    $totalRefsCount = 0;
                    $subAgents = Agent::where('ref_by', $sAgent->id)->get();
                    $totalAgents = $subAgents->count();
                    if($totalAgents){
                        foreach($subAgents as $agent){
                            $totalRefsCount += $agent->totalValidReferrer();
                        }
                    }
                    if($totalRefsCount){
                        $newRefs = $totalRefsCount - $sAgent->sg_ref_total;
                        $valid = false;
                        if( ($newRefs/$totalAgents) > 0){
                            $valid = true;
                        }
                        if($newRefs >= 0){
                            $gcoin = intval($newRefs/$set->sg_trprgc);
                            if($gcoin > 0 && $valid){
                                $sAgent->rgold_deca += ($gcoin * $set->sg_prgc);
                                $sAgent->sg_ref_total += $newRefs;
                                $sAgent->save();
                            }
                        }else{
                            $sAgent->sg_ref_total = $totalRefsCount;
                            $sAgent->save();
                        }
                    }
                }
            }
        }
    }
}

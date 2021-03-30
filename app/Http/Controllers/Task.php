<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
                if($total > 0){
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
        $days = 90;
        $grace = 30;
        $super_pkg_id = 4;
        $sAs = SuperAssociate::where('status', 0)->get();
        foreach($sAs as $sA){
            if($sA->created_at->diffInDays() <= $days){
                #allow flow
            }else{
                if($sA->last_grace != '' && Carbon::parse($sA->last_grace)->diffInDays() <= $grace){
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
        $ranks = Ranks::all();
        $last_rank = 10;
        $no_rank = 0;
        foreach($ranks as $rank){
            $users = User::where([
                ['rank_id', '<>', $last_rank],
                ['rank_id', '<>', $no_rank]
            ])->get();
            foreach($users as $user){
                $cpv = $user->cpv;
                if($rank->name != 'director'){
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
                    $user->self::$pend_trx_balance += $rank->prize;
                    $user->self::$total_loan_balance += $user->self::$loan_elig_balance;
                    $user->self::$loan_elig_balance = 0;
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$rank->prize,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$pend_trx_balance,
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
    public function loan()
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
    public function gracedLoan()
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
       ])->orderBy('created_at', 'asc')->get();
       if($givers->count()){
            foreach ($givers as $giver) {
                if(Carbon::parse($giver->lastg)->diffInHours() >= 24){
                    $receiver = GsClub::where([
                        ['status', 0],
                        ['g', 0],
                        ['gbal', $giver->gbal],
                        ['r_count', '<=', $r_count]
                    ])->orderBy('created_at', 'asc')->first();
                    switch ($giver->gbal) {
                        case 1500:
                            $pay_back = 2500;
                            $r_count = 7;
                            $days = 7;
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        break;
                        case 2500:
                            $pay_back = 7500;
                            $r_count = 7;
                            $days = 15;
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        break;
                        case 7500:
                            $pay_back = 30500;
                            $r_count = 7;
                            $days = 15;
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        break;
                        case 30500:
                            $pay_back = 83000;
                            $r_count = 6;
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        break;
                        case 83000:
                            $pay_back = 215000;
                            $r_count = 5;
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        case 215000:
                            $pay_back = 460000;
                            $r_count = 4;
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        break;
                        case 460000:
                            $pay_back = 580000;
                            $r_count = 3;
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        case 580000:
                            $pay_back = 19750;
                            $r_count = 2;
                            $days = 
                            self::gsclubR($giver, $r_count, $pay_back, $receiver, $days);
                        break;
                        default:
                        // code...
                        break;
                    }
                }
            }
        }
    }
    /**
     *Get GsClub receiver
     *
     * @return void
    */
    public static function gsclubR(GsClub $giver, $r_count, $pay_back, GsClub $receiver, $days)
    {
        if($receiver){
            if( Carbon::parse($receiver->lastr)->diffInDays() >= $days ){

                $receiver->r_count+=1;
                if($receiver->r_count >= $r_count){
                    $total = $giver->gbal * $r_count;
                    $receiver->wbal += $total - $pay_back;
                    $receiver->gbal = $pay_back;
                    $receiver->r_count = 0;
                    #convert receiver to a giver
                    $receiver->g = 1;
                    $receiver->lastg = Carbon::now();
                }
                $notEligible = false;
                if($giver->gbal == 7500){

                    if($receiver->user->totalValidRef() < 1)
                        $notEligible = true;

                }elseif($giver->gbal == 30500){

                    if($receiver->user->totalValidRef() < 3)
                        $notEligible = true;

                }elseif($giver->gbal == 83000){

                    if($receiver->user->totalValidRef() < 6)
                        $notEligible = true;

                }elseif($giver->gbal == 215000){

                    if($receiver->user->totalValidRef() < 12)
                        $notEligible = true;

                }elseif($giver->gbal == 460000){

                    if($receiver->user->totalValidRef() < 24)
                        $notEligible = true;

                }elseif($giver->gbal == 580000){

                    if($receiver->user->totalValidRef() < 50)
                        $notEligible = true;

                    $receiver->gbal = 1500;
                    $receiver->circle += 1;
                    $receiver->status = 1;
                }
                if($notEligible){
                    $receiver = GsClub::where([
                        ['status', 0],
                        ['g', 0],
                        ['gbal', $giver->gbal],
                        ['r_count', '<=', $r_count],
                        ['id', '<>', $receiver->id]
                    ])->orderBy('created_at', 'asc')->first();
                    self::gsclubR($giver, $r_count, $pay_back, $receiver);
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
    }
    /**
     * Personal Perfomance point
     *
     * @return void
    */
    public function ppp()
    {
        $acheived = 1;
        $needGrace = 2;
        $faild = 3;
        $reward = 20000;
        $ppps = PPP::where('status', 0);
        if($ppps->exists()){
            foreach($ppps as $ppp){
                if($ppp->created_at->diffInDays() >= 90){
                    if($user = User::find($ppp->user_id)){
                        if($user->totalValidRef() >= 50){
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
                                if(Carbon::parse($ppp->graced_at)->diffInDays() >= 30){
                                    $ppp->grace += 1;
                                    if($ppp->grace >= 3)
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
    public function rPPP()
    {
        $acheived = 1;
        $pv = 100000;
        $reward = 50000;
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
}

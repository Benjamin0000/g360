<?php
namespace App\Http\Controllers;
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
     * Share pending wallet
     *
     * @return Void
    */
    public static function sharePendingWallet()
    { 
        $f = Helpers::getRegData('p_share_formular');
        if(!$f)return;
        $f = json_decode('[' . $f . ']', true);
        $last_pkg_id = Package::orderBy('id', 'DESC')->first()->id;
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $total = $user->pend_balance;
                $billForLoan = false;
                $billForPkg = false;
                $type = '';
                if($total >= 100){
                    if($user->pkg_id != $last_pkg_id)
                        $billForPkg = true;
                    if($user->haveUnPaidLoan())
                        $billForLoan = true;
                    if($billForLoan && $billForPkg){
                        $formular = $f;
                    }elseif($billForPkg || $billForLoan){
                        if($billForLoan)
                            $type = 'loan';
                        else
                            $type = 'pkg';
                        $formular = [ $f[0], $f[1], $f[2]+$f[3], $f[4] ];
                    }else{
                        $formular = [ $f[0], $f[1],  0, $f[2] + $f[3] + $f[4] ];
                    }
                    self::pSharing($user, $formular, $type);
                }
            }
        }
    }
    public static function pSharing(User $user, $formular, $type = '')
    {
        $total = $user->pend_balance;
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
                $loan_bal -= $excess;
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$loan_bal,
                    'gnumber'=>$user->gnumber,
                    'name'=>'pend_balance',
                    'type'=>'debit',
                    'description'=>self::$cur.number_format($loan_bal).' for loan'
                ]);
            }
        }
        $trx_amount = $trx_bal;
        if($pkg_bal)
            $user->pkg_balance += $pkg_bal;
        $user->award_point += $award_pt;
        $user->with_balance += $w_bal;
        $user->trx_balance += ($trx_amount + $excess);
        $user->pend_balance = 0;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$trx_amount,
            'gnumber'=>$user->gnumber,
            'name'=>'trx_balance',
            'type'=>'credit',
            'description'=>self::$cur.number_format($trx_amount).' daily earning'
        ]);
        if($excess){
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$excess,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_balance',
                'type'=>'credit',
                'description'=>self::$cur.number_format($excess).' Loan excess'
            ]); 
        }
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$award_pt,
            'gnumber'=>$user->gnumber,
            'name'=>'award_point',
            'type'=>'credit',
            'description'=>$award_pt.' award point from daily earning'
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$w_bal,
            'gnumber'=>$user->gnumber,
            'name'=>'with_balance',
            'type'=>'credit',
            'description'=>self::$cur.number_format($w_bal).' daily earning'
        ]);
        if($pkg_bal){
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$pkg_bal,
                'gnumber'=>$user->gnumber,
                'name'=>'pkg_balance',
                'type'=>'credit',
                'description'=>self::$cur.number_format($pkg_bal).' daily earning'
            ]);
        }
    }
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
                    continue; #disallow flow
                }
            }
            if($user = User::find($sA->user_id)){
                $cpv = $user->cpv;
                if($cpv >= $pv){
                    if(Helpers::checkLegBalance($user, $cpv))
                        $sA->status = 2; #made it
                    else
                        $sA->balance_leg = 1; #balance leg

                    if($user->rank_id != 0 || $cpv >= $rank->next()->pv)
                        $sA->status = 4;
                    
                    $sA->save();
                }else{
                    if($sA->balance_leg == 1){
                        $sA->balance_leg = 0;
                        $sA->save();
                    }
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
        $sA_rank_id = 1;
        $ranks = Rank::where('id', '<>', $sA_rank_id)->get();
        $last_rank = Rank::orderBy('id', 'DESC')->first()->id;
        foreach($ranks as $rank){
            $users = User::where([
                ['rank_id', '<>', $last_rank],
                ['rank_id', '<', $rank->id]
            ])->get();
            foreach($users as $user){
                $cpv = $user->cpv;
                if($rank->id != $last_rank){
                    if($cpv >= $rank->pv && $cpv < $rank->next()->pv){
                        #allow the flow
                    }else{
                        continue; #disallow the flow
                    }
                }else{
                    if($cpv < $rank->pv)
                        continue; #disallow the flow
                }
                if(Helpers::checkLegBalance($user, $cpv)){
                    if($user->rank_id == 0 && $user->superAssoc->status != 3){
                        if($user->superAssoc->status != 4){
                            $user->superAssoc->status = 4;
                            $user->superAssoc->save();
                        }
                    }
                    #credit user
                    $user->rank_id = $rank->id;
                    $user->pend_balance += $rank->prize;
                    $user->total_loan_balance += $user->loan_elig_balance;
                    $user->loan_elig_balance = 0;
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$rank->prize,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$pend_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.$rank->prize.
                        ' '.$rank->name.' reward'
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
                    
                if($last_payed->diffInMinutes() >= self::$month_end){
                    if($user = User::find($lmp->user_id)){
                        $amount = $lmp->amount;
                        $user->with_balance += $amount;
                        $user->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$amount,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$with_balance,
                            'type'=>'credit',
                            'description'=>self::$cur.$amount.
                            ' '.$lmp->name.' leadership monthly bonus'
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
    public static function creditGurantors(Loan $loan)
    {
        if($loan->garant){
            $garant = User::find($loan->garant);
            if($garant){
                $garant->loan_elig_balance += $loan->garant_amt;
                $garant->save();
            }
        }else{
            #a ranked user
            if($user = User::find($loan->user_id)){
                $user->loan_elig_balance += $loan->amount;
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
       $switch = Helpers::gsTeamSwitch();
       $givers = GsClub::where([
          ['status', 0],
          ['g', 1],
          ['switch', $switch]
       ])->orderBy('created_at', 'ASC')->take(50);
       if($givers->exists()){
            foreach ($givers->get() as $giver) {
                if($gtr = GTR::where('amount', $giver->gbal)->first()){
                    if(Carbon::parse($giver->lastg)->diffInMinutes() >= $gtr->g_hours)
                        self::gsclubR($giver, $gtr);
                }
                if($switch == 0)
                    $giver->switch = 1;
                else
                    $giver->switch = 0;
                $giver->save();
            }
        }else{
            if($switch == 0){
                Helpers::gsTeamSwitch(1);
            }else{
                Helpers::gsTeamSwitch(0);
            }
        }
    }
    /**
     *Get GsClub receiver
     *
     * @return void
    */
    private static function gsclubR(GsClub $giver, GTR $gtr, $r_ids = [])
    {
        $first_amt = GTR::orderBy('id', 'ASC')->first()->amount;
        $last = GTR::orderBy('id', 'DESC')->first()->id;
        $r_count = $gtr->r_count;
        $pay_back = $gtr->pay_back;
        $hours = $gtr->r_hours;
        $dateCheck = Carbon::now()->subMinutes($hours)->toDateTimeString();
        $receiver = GsClub::where([
            ['status', 0],
            ['g', 0],
            ['gbal', $giver->gbal]
        ])->whereNotIn('id', $r_ids)
        ->where('lastr', '<=', $dateCheck)
        ->orderBy('created_at', 'ASC')->first();
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
            #default user
            if($receiver->def){
               $formular = json_decode('['.$receiver->def_refs.']', true);
               if( isset($formular[$gtr->id -1]) ){
                    $req_ref = $formular[$gtr->id -1];
                    if($req_ref > $receiver->user->totalValidRef())
                        $notEligible = true;
                    else 
                        $notEligible = false;
               }else{
                    $notEligible = false;
               }
            }
            if($notEligible){
                array_push($r_ids, $receiver->id);
                self::gsclubR($giver, $gtr, $r_ids);
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
                    $giver->user->fname.' '.$giver->user->lname.'['.$giver->user->gnumber.']'
                ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$giver->user_id,
                    'amount'=>$giver->gbal,
                    'type'=>1,
                    'description'=>self::$cur.number_format($giver->gbal)." Sent to ".
                    $receiver->user->fname.' '.$receiver->user->lname.'['.$receiver->user->gnumber.']'
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
            foreach($ppps->get() as $ppp){
                if($ppp->created_at->diffInMinutes() >= $required_minutes){
                    if($user = User::find($ppp->user_id)){
                        if($user->totalValidRef() >= $required_referrals){
                            if($ppp->grace == 0){
                                $user->pend_balance += $reward;
                                $user->save();
                                WalletHistory::create([
                                    'id'=>Helpers::genTableId(WalletHistory::class),
                                    'user_id'=>$user->id,
                                    'amount'=>$reward,
                                    'gnumber'=>$user->gnumber,
                                    'name'=>self::$pend_balance,
                                    'type'=>'credit',
                                    'description'=>self::$cur.$reward.
                                    ' personal performance Reward'
                                ]);
                            }
                            $ppp->status = $acheived;
                            $ppp->save();
                        }else{
                            if($ppp->graced_at != ''){
                                if(Carbon::parse($ppp->graced_at)->diffInMinutes() >= $graced_minutes){
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
                    if($value > 0 && Helpers::checkLegBalance($user, $user->cpv)){
                        $amt = $reward * $value;
                        $user->pend_balance += $amt;
                        $user->save();
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$amt,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$pend_balance,
                            'type'=>'credit',
                            'description'=>self::$cur.$amt.
                            ' personal performance point'
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
                    $user->with_balance += $int_amt;
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
                $user->with_balance += $returned;
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

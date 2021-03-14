<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TrackFreeUser;
use App\Models\WalletHistory;
use App\Models\MpPoint;
use App\Models\CircleBonus;
use App\Models\SuperAssociate;
use App\Models\User;
use App\Models\Package;
use App\Models\Lmp;
use App\Models\Rank;
use App\Models\Reward;
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
     * Track free users;
     * 
     * @return Void
    */
    public static function sharePendingWallet()
    {
        $last_pkg_id = 7;
                # [award_pt, with, pkg_loan, trx]
        $share_p_one = [20, 5,  25, 50]; #no loan
        $share_p_two = [20, 5,  0, 75]; #last_pkg
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $total = $user->self::$pend_balance;
                $pend_trx = $user->self::$pend_trx_balance;
                $loan_pkg_bal = 0;
                $trx_bal = 0;
                $w_bal = 0;
                $award_pt = 0;
                $bill_pkg = true;
                if($total > 0){
                    if($user->pkg_id  == $last_pkg_id){
                        if(!$user->haveUnPaidLoan())
                            $bill_pkg = false;
                    }
                    if($bill_pkg == false)
                        $trx_bal = ($share_p_two[3] / 100) * $total;
                    else{
                        $trx_bal = ($share_p_one[3] / 100) * $total;
                        $loan_pkg_bal = ($share_p_one[2] / 100) * $total;
                    }
                    $award_pt = ($share_p_one[0] / 100) * $total;
                    $w_bal = ($share_p_one[1] / 100) * $total;
                    if($loan_pkg_bal){
                        $user->self::$loan_pkg_balance += $loan_pkg_bal;
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$loan_pkg_bal,
                            'gnumber'=>$user->gnumber,
                            'name'=>self::$loan_pkg_balance,
                            'type'=>'credit',
                            'description'=>self::$cur.number_format($loan_pkg_bal).' daily earning'
                        ]);
                    }
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
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$trx_bal,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$trx_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.number_format($trx_bal).' daily earning'
                    ]);
                    $user->self::$award_point += $award_pt;
                    $user->self::$with_balance += $w_bal;
                    $user->self::$trx_balance += $trx_bal;
                    $user->self::$pend_balance = 0;
                    if($pend_trx > 0){
                        $user->self::$trx_balance += $pend_trx;
                    }
                    $user->save();
                }
            }
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
        $inc_pkg_id = 1;
        $fee = 0;
        $users = User::where([
            ['status', 1],
            ['pkg_id', '<' , $last_pkg_id]
        ]);
        if($users->exists()){
            $users = $users->get();
            foreach($users as $user){
                if($current_pkg = Package::find($user->pkg_id)){
                    if($nxt_package = Package::find($user->pkg_id + $inc_pkg_id)){
                        $amount = $nxt_package->amount - $current_pkg->amount;
                        if($percent = $user->free_t_fee)
                            $fee = ($percent/100)*$amount;
                        if($user->self::$loan_pkg_balance >= $amount){
                            if($fee){
                                $total = $amount+$fee;
                                if($user->self::$loan_pkg_balance < $total){
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
                                    $user->self::$loan_pkg_balance-=$fee;
                                    $user->save();
                                    WalletHistory::create([
                                        'id'=>Helpers::genTableId(WalletHistory::class),
                                        'user_id'=>$user->id,
                                        'amount'=>$fee,
                                        'gnumber'=>$user->gnumber,
                                        'name'=>self::$loan_pkg_balance,
                                        'type'=>'debit',
                                        'description'=>self::$cur.$fee.' Debited for '.ucfirst($nxt_package->name).' package '.$percent.'% fee'
                                    ]);
                                }
                            }
                            $user->free_t_fee = 0; #clear fee
                            $user->self::$loan_pkg_balance-=$amount;
                            $user->save();
                            WalletHistory::create([
                                'id'=>Helpers::genTableId(WalletHistory::class),
                                'user_id'=>$user->id,
                                'amount'=>$amount,
                                'gnumber'=>$user->gnumber,
                                'name'=>self::$loan_pkg_balance,
                                'type'=>'debit',
                                'description'=>self::$cur.$amount.' Debited for '.ucfirst($nxt_package->name).' package'
                            ]);
                            $nxt_package->activate($user, 'none', 'LOAN-PKG Wallet auto upgrade');
                        }    
                    }
                }
            }
        }
    }
    /**
     * Monthly performance point
     *
     * @return void
    */
    public static function mpPoint()
    {
        $pv = 360;
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $cpv = $user->cpv;
                $mpPoint = MpPoint::where('user_id', $user->id)->first();
                if(!$mpPoint)
                    $mpPoint = MpPoint::create(['user_id'=>$user->id]);
                
                if( $cpv >= ($pv*$mpPoint->times) ){
                    $mpPoint->point+=1;
                    $mpPoint->times+=1;
                    $mpPoint->save();
                }
            }
        }
    }
    /**
     * Credit Monthly performance bonus
     *
     * @return void
    */
    public static function creditMpPoint()
    {
        $bonus = 400;
        $mpPoints = MpPoint::all();
        foreach($mpPoints as $mpPoint){
            if($user = User::find($mpPoint->user_id)){
                if($mpPoint->point > 0){
                    $bonus = $bonus*$mpPoint->point;
                    $user->self::$pend_balance += $bonus;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$bonus,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$pend_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.$bonus.'earned from monthly perfomance bonus'
                    ]);
                    $mpPoint->earn_times += 1;
                }
            }
            $mpPoint->point = 0;
            $mpPoint->save();
        }
    }
     /**
     * Circle bonus
     *
     * @return void
    */   
    public static function circleBonus()
    {
        $pv = 18000;
        $bonus = 100000;
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $cpv = $user->cpv;
                $cBonus = CircleBonus::where('user_id', $user->id)->first();
                if(!$cBonus)
                    $cBonus = CircleBonus::create(['user_id'=>$user->id]);
                $total_pv = ($pv*$cBonus->times);
                if($cpv >= $total_pv){
                    #check for leg balancing
                    if(!Helpers::checkLegBalance($user, $total_pv))
                        return;
                    $user->self::$pend_balance += $bonus;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$bonus,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$pend_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.$bonus.'earned from Circle bonus PV'
                    ]);
                    $cBonus->times += 1;
                    $cBonus->point = 0;
                }else{
                    if($cpv >= $total_pv)
                        $point = $total_pv;
                    else 
                        $point = $pv - ($total_pv - $cpv);
                    $cBonus->point = $point;
                }
                $cBonus->save();
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
        $pv = 9000;
        $days = 60;
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
                    if(Helpers::checkLegBalance($user, $pv))
                        $sA->status = 2; #made it
                    else
                        $sA->balance_leg = 1; #balance leg
                    $sA->save();
                }
            }else{
                $sA->delete();
                return;
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
        $month_end = 27;
        $lmps = Lmp::where('status', 0)->get();
        if($lmps->count()){
            foreach($lmps as $lmp){
                if($lmp->last_payed != '')
                    $last_payed = Carbon::parse($lmp->last_payed);
                else 
                    $last_payed = $lmp->created_at;
                if($last_payed->diffInDays() >= $month_end){
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
}

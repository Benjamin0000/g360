<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackFreeUser;
use App\Models\WalletHistory;
use App\Models\WeeklyBonus;
use App\Models\CircleBonus;
use App\Models\User;
use App\Models\Package;
use App\Http\Helpers;
use Carbon\Carbon;

class Task extends Controller
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
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $trx_balance = Helpers::TRX_BALANCE;
        $with_balance = Helpers::WITH_BALANCE;
        $pend_balance = Helpers::PEND_BALANCE;
        $loan_pkg_balance = Helpers::LOAN_PKG_BALANCE;
        $award_point = Helpers::AWARD_POINT;

        $last_pkg_id = 7;
                # [award_pt, with, pkg_loan, trx]
        $share_p_one = [20, 5,  25, 50]; #no loan
        $share_p_two = [20, 5,  0, 75]; #last_pkg
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $total = $user->$pend_balance;
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
                        $user->$loan_pkg_balance += $loan_pkg_bal;
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$loan_pkg_bal,
                            'gnumber'=>$user->gnumber,
                            'name'=>$loan_pkg_balance,
                            'type'=>'credit',
                            'description'=>$cur.number_format($loan_pkg_bal).' daily earning'
                        ]);
                    }
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$award_pt,
                        'gnumber'=>$user->gnumber,
                        'name'=>$award_point,
                        'type'=>'credit',
                        'description'=>$award_pt.' award point from daily earning'
                    ]);                    
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$w_bal,
                        'gnumber'=>$user->gnumber,
                        'name'=>$with_balance,
                        'type'=>'credit',
                        'description'=>$cur.number_format($w_bal).' daily earning'
                    ]);
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$trx_bal,
                        'gnumber'=>$user->gnumber,
                        'name'=>$trx_balance,
                        'type'=>'credit',
                        'description'=>$cur.number_format($trx_bal).' daily earning'
                    ]);
                    $user->$award_point += $award_pt;
                    $user->$with_balance += $w_bal;
                    $user->$trx_balance += $trx_bal;
                    $user->$pend_balance = 0;
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
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $loan_pkg_balance = Helpers::LOAN_PKG_BALANCE;
        $trx_balance = Helpers::TRX_BALANCE;
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
                        if($user->$loan_pkg_balance >= $amount){
                            if($fee){
                                $total = $amount+$fee;
                                if($user->$loan_pkg_balance < $total){
                                    if($user->$trx_balance < $fee)
                                       return;
                                    else{
                                        $user->$trx_balance-=$fee;
                                        $user->save();
                                        WalletHistory::create([
                                            'id'=>Helpers::genTableId(WalletHistory::class),
                                            'user_id'=>$user->id,
                                            'amount'=>$fee,
                                            'gnumber'=>$user->gnumber,
                                            'name'=>$trx_balance,
                                            'type'=>'debit',
                                            'description'=>$cur.$fee.' Debited for '.ucfirst($nxt_package->name).' package '.$percent.'% fee'
                                        ]);
                                    }
                                }else{
                                    $user->$loan_pkg_balance-=$fee;
                                    $user->save();
                                    WalletHistory::create([
                                        'id'=>Helpers::genTableId(WalletHistory::class),
                                        'user_id'=>$user->id,
                                        'amount'=>$fee,
                                        'gnumber'=>$user->gnumber,
                                        'name'=>$loan_pkg_balance,
                                        'type'=>'debit',
                                        'description'=>$cur.$fee.' Debited for '.ucfirst($nxt_package->name).' package '.$percent.'% fee'
                                    ]);
                                }
                            }
                            $user->free_t_fee = 0; #clear fee
                            $user->$loan_pkg_balance-=$amount;
                            $user->save();
                            WalletHistory::create([
                                'id'=>Helpers::genTableId(WalletHistory::class),
                                'user_id'=>$user->id,
                                'amount'=>$amount,
                                'gnumber'=>$user->gnumber,
                                'name'=>$loan_pkg_balance,
                                'type'=>'debit',
                                'description'=>$cur.$amount.' Debited for '.ucfirst($nxt_package->name).' package'
                            ]);
                            $nxt_package->activate($user, 'none', 'LOAN-PKG Wallet auto upgrade');
                        }    
                    }
                }
            }
        }
    }

    public static function weeklyBonus()
    {
        $pv = 360;
        $bonus = 500;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $pend_balance = Helpers::PEND_BALANCE;
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $wBonus = WeeklyBonus::where('user_id', $user->id)->first();
                
                if(!$wBonus)
                    $wBonus = WeeklyBonus::create(['user_id'=>$user->id]);

                $last_check = $wBonus->last_check;

                if(!$last_check)
                    $last_check = $wBonus->created_at;

                $total_pv = WalletHistory::where([
                    ['user_id', $user->id],
                    ['name', 'cpv'],
                    ['created_at', '>=', $last_check]
                ])->sum('amount');

                if( $total_pv >=  $pv){
                    $user->$pend_balance += $bonus;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$bonus,
                        'gnumber'=>$user->gnumber,
                        'name'=>$pend_balance,
                        'type'=>'credit',
                        'description'=>$cur.$bonus.'earned from weekly bonus'
                    ]);
                }
                $wBonus->last_check = Carbon::now();
                $wBonus->times+=1;
                $wBonus->save();
            }
        }
    }

    public static function circleBonus()
    {
        $pv = 3600;
        $bonus = 4000;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $pend_balance = Helpers::PEND_BALANCE;
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $cpv = $user->cpv;
                $cBonus = CircleBonus::where('user_id', $user->id)->first();
                if(!$cBonus)
                    $cBonus = CircleBonus::create(['user_id'=>$user->id]);
                
                if( $cpv >= ($pv*$cBonus->times) ){
                    $user->$pend_balance += $bonus;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$bonus,
                        'gnumber'=>$user->gnumber,
                        'name'=>$pend_balance,
                        'type'=>'credit',
                        'description'=>$cur.$bonus.'earned from Circle bonus'
                    ]);
                    $cBonus->times+=1;
                    $cBonus->save();
                }
            }
        }
    }

    public static function laurelBonus()
    {

    }

    public static function processRanking()
    {
        
    }


}

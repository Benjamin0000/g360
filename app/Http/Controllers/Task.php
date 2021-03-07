<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackFreeUser;
use App\Models\WalletHistory;
use App\Models\User;
use App\Models\Package;
use App\Http\Helpers;

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
        return ;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $free_pkg_id = 1;
                # [g_pt, with, pkg, trx]
        $prem_p = [20, 5,  25, 50];
        $users = User::all();
        if($users->count()){
            foreach($users as $user){
                $total = $user->p_balance;
                $pkg_bal = 0;
                $trx_bal = 0;
                $w_bal = 0;
                if($total > 0){
                    if($user->pkg_id == $free_pkg_id){
                        $pkg_bal = ($free_p[0] / 100) * $total;
                        $trx_bal = ($free_p[2] / 100) * $total;
                    }elseif($user->pkg_id > $free_pkg_id){
                        $pkg_bal = ($prem_p[0] / 100) * $total;
                        $w_bal = ($prem_p[1] / 100) * $total;
                        $trx_bal = ($prem_p[2] / 100) * $total;
                    }
                    if($w_bal){
                        $user->w_balance += $w_bal;
                        WalletHistory::create([
                            'id'=>Helpers::genTableId(WalletHistory::class),
                            'user_id'=>$user->id,
                            'amount'=>$w_bal,
                            'gnumber'=>$user->gnumber,
                            'name'=>Helpers::WITH_BALANCE,
                            'type'=>'credit',
                            'description'=>$cur.number_format($w_bal).' daily earning'
                        ]);
                    }
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$pkg_bal,
                        'gnumber'=>$user->gnumber,
                        'name'=>Helpers::PKG_BALANCE,
                        'type'=>'credit',
                        'description'=>$cur.number_format($pkg_bal).' daily earning'
                    ]);
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$trx_bal,
                        'gnumber'=>$user->gnumber,
                        'name'=>Helpers::TRX_BALANCE,
                        'type'=>'credit',
                        'description'=>$cur.number_format($trx_bal).' daily earning'
                    ]);                                        
                    $user->pkg_balance += $pkg_bal;
                    $user->t_balance += $trx_bal;
                    $user->p_balance = 0;
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
                        if($user->pkg_balance >= $amount){
                            if($fee){
                                $total = $amount+$fee;
                                if($user->pkg_balance < $total){
                                    if($user->t_balance < $fee)
                                       return;
                                    else{
                                        $user->t_balance-=$fee;
                                        $user->save();
                                        WalletHistory::create([
                                            'id'=>Helpers::genTableId(WalletHistory::class),
                                            'user_id'=>$user->id,
                                            'amount'=>$fee,
                                            'gnumber'=>$user->gnumber,
                                            'name'=>Helpers::TRX_BALANCE,
                                            'type'=>'debit',
                                            'description'=>$cur.$fee.' Debited for '.ucfirst($nxt_package->name).' package '.$percent.'% fee'
                                        ]);
                                    }
                                }else{
                                    $user->pkg_balance-=$fee;
                                    $user->save();
                                    WalletHistory::create([
                                        'id'=>Helpers::genTableId(WalletHistory::class),
                                        'user_id'=>$user->id,
                                        'amount'=>$fee,
                                        'gnumber'=>$user->gnumber,
                                        'name'=>Helpers::PKG_BALANCE,
                                        'type'=>'debit',
                                        'description'=>$cur.$fee.' Debited for '.ucfirst($nxt_package->name).' package '.$percent.'% fee'
                                    ]);
                                }
                            }
                            $user->free_t_fee = 0; #clear fee
                            $user->pkg_balance-=$amount;
                            $user->save();
                            WalletHistory::create([
                                'id'=>Helpers::genTableId(WalletHistory::class),
                                'user_id'=>$user->id,
                                'amount'=>$amount,
                                'gnumber'=>$user->gnumber,
                                'name'=>Helpers::PKG_BALANCE,
                                'type'=>'debit',
                                'description'=>$cur.$amount.' Debited for '.ucfirst($nxt_package->name).' package'
                            ]);
                            $nxt_package->activate($user, 'none', 'PKG Wallet auto upgrade');
                        }    
                    }
                }
            }
        }
    }
}

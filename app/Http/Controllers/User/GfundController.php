<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;

class GfundController extends Controller
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
        return view('user.gfund.index');
    }

    /**
     * Wallet transfer
     *
     * @return \Illuminate\Http\Response
     */
    public function walletTransfer(Request $request)
    { 
        if(!$request->ajax())
            return;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $amount = (float)$request->amount;
        $min = 1000;
        if($amount <= 0)
            return ['msg'=>"Please enter a valid amount"];
        if($amount < $min)
            return ['msg'=>"minimum transfer amount is ".$cur.$min];

        $user = Auth::user();
        if($user->w_balance < $amount)
            return ['msg'=>"Insufficient fund for transfer"];

        $wallet_transfer = 'w_transfer';
        $amount = abs($amount); #👈 no need for this line, it's just for fun sake 😄
        switch($request->wallet){
            case 'tw': 
                $type = 'trx_transfer';
                $wallet = 'T-wallet';
                $sent = Helpers::TRX_BALANCE;
                $user->t_balance += $amount;
            break; 
            case 'pkg': 
                $type = 'pkg_transfer';
                $wallet = 'PKG-wallet';
                $sent = Helpers::PKG_BALANCE;
                $user->pkg_balance += $amount;
            break;
            default: 
                return ['msg'=>"Please select a valid wallet"];
        }
        $user->w_balance -= $amount;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$amount,
            'gnumber'=>$user->gnumber,
            'name'=>Helpers::WITH_BALANCE,
            'type'=>$type,
            'description'=>$cur.$amount.' transfered to '.$wallet
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$amount,
            'gnumber'=>$user->gnumber,
            'name'=>$sent,
            'type'=>$wallet_transfer,
            'description'=>$cur.$amount.' received from T-wallet'
        ]);
        return ['status'=>1, 'msg'=>"Transfer successful", 'bal'=>$user->w_balance];
    }


}

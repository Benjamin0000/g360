<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\User;
use App\Models\WalletHistory;

class GfundController extends Controller
{
    public const CUR = Helpers::LOCAL_CURR_SYMBOL;
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
     * Transfer from withdrawal wallet
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawalWalletTransfer(Request $request)
    { 
        if(!$request->ajax())return;

        $trx_balance = Helpers::TRX_BALANCE;
        $pkg_balance = Helpers::PKG_BALANCE;
        $with_balance = Helpers::WITH_BALANCE;

        $last_pkg_id = 7;
        $amount = (float)$request->amount;
        $min = 1000;
        if($amount <= 0)
            return ['msg'=>"Please enter a valid amount"];
        if($amount < $min)
            return ['msg'=>"Minimum transfer amount is ".self::CUR.$min];

        $user = Auth::user();
        if($user->$with_balance < $amount)
            return ['msg'=>"Insufficient fund for transfer"];

        $amount = abs($amount); #👈 no need for this line, it's just for fun sake 😄
        switch($request->wallet){
            case 'tw': 
                $wallet = 'TRX-wallet';
                $sent = $trx_balance;
                $user->$trx_balance += $amount;
            break; 
            case 'pkg':
                if($user->pkg_id == $last_pkg_id)
                    return ['msg'=>"Can't make transfer to PKG-wallet"];
                $wallet = 'PKG-wallet';
                $sent = $pkg_balance;
                $user->$pkg_balance += $amount;
            break;
            default: 
                return ['msg'=>"Please select a valid wallet"];
        }
        $user->$with_balance -= $amount;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$amount,
            'gnumber'=>$user->gnumber,
            'name'=>$with_balance,
            'type'=>'debit',
            'description'=>self::CUR.$amount.' transfered to '.$wallet
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$amount,
            'gnumber'=>$user->gnumber,
            'name'=>$sent,
            'type'=>'credit',
            'description'=>self::CUR.$amount.' received from W-wallet'
        ]);
        return [
            'status'=>1, 
            'msg'=>"Transfer successful", 
            'bal'=>number_format($user->$with_balance, 2, '.', ','),
            'bal3'=>number_format($user->$trx_balance, 2, '.', ',')
        ];
    }
    /**
     * Transfer from transaction wallet
     *
     * @return \Illuminate\Http\Response
     */ 
    public function trxWalletTransfer(Request $request)
    {
        if(!$request->ajax())return;
        $trx_balance = Helpers::TRX_BALANCE;
        $with_balance = Helpers::WITH_BALANCE;
        $amount = (float)$request->amount;
        $min = 1000;

        if($amount <= 0)
            return ['msg'=>"Please enter a valid amount"];
        if($amount < $min)
            return ['msg'=>"Minimum transfer amount is ".self::CUR.$min];
        if(!$request->wallet)
            return ['msg'=>"Select a wallet to transfer to"];

        $user = Auth::user();
        if($user->$trx_balance < $amount)
            return ['msg'=>"Insufficient fund for transfer"];

        $fee = 0.05 * $amount; # 5% fee charge
        $total = $amount + $fee;
        if($user->$trx_balance < $total)
            return ['msg'=>"Insufficient fund for the 5% fee"];
        
        if($request->wallet == 'w')
            $user->$with_balance += $amount;
        else 
            return ['msg'=>"Wallet option not supported "];

        $user->$trx_balance -= $total;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$amount,
            'gnumber'=>$user->gnumber,
            'name'=>$with_balance,
            'type'=>'credit',
            'description'=>self::CUR.$amount.' transfered from TRX-wallet'
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$amount,
            'gnumber'=>$user->gnumber,
            'name'=>$trx_balance,
            'type'=>'debit',
            'description'=>self::CUR.$amount.' transfered to W-wallet'
        ]);
        return [
            'status'=>1, 
            'msg'=>"Transfer successful", 
            'bal'=>number_format($user->$trx_balance, 2, '.', ','),
            'bal2'=>number_format($user->$with_balance, 2, '.', ',')
        ];
    }
    /**
     * Validate Transaction before transfer
     *
     * @return \Illuminate\Http\Response
     */     
    private static function validateTransfer(Request $request)
    {
        if(!$request->ajax())return;

        $with_balance = Helpers::WITH_BALANCE;

        $min = 1000; 
        if(!$amount = (float)$request->amount)
            return ['msg'=>"Please enter a valid amount"];

        if($amount <= 0)
            return ['msg'=>"Please enter a valid amount"];

        if($amount < $min)
            return ['msg'=>"Minimum transfer amount is ".self::CUR.$min];

        $sender = Auth::user();
        if($sender->$with_balance < $amount)
            return ['msg'=>"Insufficient fund for transfer"];
            
        if($gnumber = $request->gnumber){
            $recipient = User::where([ 
                ['gnumber', $gnumber], 
                ['status', 1],
                ['gnumber', '<>', $sender->gnumber],
                ['id', '<>', $sender->id]  
            ])->first();
            if($recipient){
                return [
                    'name'=>$recipient->fname.' '.$recipient->lname,
                    'amount'=>$amount,
                    'gnumber'=>$recipient->gnumber,
                    'username'=>$recipient->username
                ];
            }else
                return ['msg'=>"Recipient not found"];
        }else 
            return ['msg'=>"Enter a G-number"];
    }
    /**
     * Get members detail before transfer
     *
     * @return \Illuminate\Http\Response
     */    
    public function getMemeberDetail(Request $request)
    {
        return self::validateTransfer($request);
    }
    /**
     * Members wallet transfer
     *
     * @return \Illuminate\Http\Response
     */
    public function transMembers(Request $request)
    {
        if(!$request->ajax())return;
        $with_balance = Helpers::WITH_BALANCE;
        $data = self::validateTransfer($request);
        if(isset($data['gnumber'])){
            $gnumber = $data['gnumber'];
            $sender = Auth::user();
            $receiver = User::where('gnumber', $gnumber)->first();
            if($receiver){
                $amount = abs($data['amount']);
                if($sender->$with_balance >= $amount){
                    $sender->$with_balance-=$amount;
                    $sender->save();
                    $receiver->$with_balance+=$amount;
                    $receiver->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$sender->id,
                        'amount'=>$amount,
                        'gnumber'=>$sender->gnumber,
                        'name'=>$with_balance,
                        'type'=>'debit',
                        'description'=>self::CUR.$amount.' sent to '.
                        $receiver->fname.' '.$receiver->lname.' ['.$receiver->gnumber.']'
                    ]);
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$receiver->id,
                        'amount'=>$amount,
                        'gnumber'=>$receiver->gnumber,
                        'name'=>$with_balance,
                        'type'=>'credit',
                        'description'=>self::CUR.$amount.' received from '.
                        $sender->fname.' '.$sender->lname.' ['.$sender->gnumber.']'
                    ]);
                    return [
                        'status'=>true, 
                        'bal'=>$sender->$with_balance,
                        'amount'=>$amount,
                        'receiver'=>$receiver->fname.' '.$receiver->lname
                    ];
                }
                return ['msg'=>"Insufficient fund for transfer"];
            }
            return ['msg'=>"Recipient not found"];
        }else
            return $data;
    }
    /**
     * Wallet transfer to bank account
     *
     * @return \Illuminate\Http\Response
     */   
    public function transBankAccount(Request $request)
    {

    }


}

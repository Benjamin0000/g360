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
     * Internal Wallet transfer
     *
     * @return \Illuminate\Http\Response
     */
    public function walletTransfer(Request $request)
    { 
        if(!$request->ajax())return;
        $last_pkg_id = 7;
        $amount = (float)$request->amount;
        $min = 1000;
        if($amount <= 0)
            return ['msg'=>"Please enter a valid amount"];
        if($amount < $min)
            return ['msg'=>"minimum transfer amount is ".self::CUR.$min];

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
                if($user->pkg_id == $last_pkg_id)
                    return ['msg'=>"Can't make transfer to PKG-wallet"];
                
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
            'description'=>self::CUR.$amount.' transfered to '.$wallet
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$amount,
            'gnumber'=>$user->gnumber,
            'name'=>$sent,
            'type'=>$wallet_transfer,
            'description'=>self::CUR.$amount.' received from T-wallet'
        ]);
        return ['status'=>1, 'msg'=>"Transfer successful", 'bal'=>$user->w_balance];
    }
    /**
     * Validate Transaction before transfer
     *
     * @return \Illuminate\Http\Response
     */     
    private static function validateTransfer(Request $request)
    {
        if(!$request->ajax())return;
        $min = 1000; 
        if(!$amount = (float)$request->amount)
            return ['msg'=>"Please enter a valid amount"];

        if($amount <= 0)
            return ['msg'=>"Please enter a valid amount"];

        if($amount < $min)
            return ['msg'=>"minimum transfer amount is ".self::CUR.$min];

        $sender = Auth::user();
        if($sender->w_balance < $amount)
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
        $data = self::validateTransfer($request);
        if(isset($data['gnumber'])){
            $gnumber = $data['gnumber'];
            $sender = Auth::user();
            $receiver = User::where('gnumber', $gnumber)->first();
            if($receiver){
                $amount = abs($data['amount']);
                if($sender->w_balance >= $amount){
                    $sender->w_balance-=$amount;
                    $sender->save();
                    $receiver->w_balance+=$amount;
                    $receiver->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$sender->id,
                        'amount'=>$amount,
                        'gnumber'=>$sender->gnumber,
                        'name'=>Helpers::WITH_BALANCE,
                        'type'=>'debit',
                        'description'=>self::CUR.$amount.' sent to '.
                        $receiver->fname.' '.$receiver->lname.' ['.$receiver->gnumber.']'
                    ]);
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$receiver->id,
                        'amount'=>$amount,
                        'gnumber'=>$receiver->gnumber,
                        'name'=>Helpers::WITH_BALANCE,
                        'type'=>'credit',
                        'description'=>self::CUR.$amount.' received from '.
                        $sender->fname.' '.$sender->lname.' ['.$sender->gnumber.']'
                    ]);
                    return [
                        'status'=>true, 
                        'bal'=>$sender->w_balance,
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

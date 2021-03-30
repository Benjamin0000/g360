<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\GsClub;
use App\Models\GsClubH;
use App\Models\WalletHistory;
class GsClubController extends G360
{
    public $formular = [1.25, 0.75, 0.5];
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
        $member = GsClub::where([ 
            ['user_id', $user->id], 
            ['status', 0]   
        ])->first();
        $histories = GsClubH::where('user_id', $user->id)->paginate(10);
        $total_his = $histories->count();
        $totalE = GsClubH::where([ ['user_id', $user->id], ['type', 0] ])->sum('amount');
        return view('user.gsclub.index', compact('member', 'histories', 'total_his', 'totalE'));
    }
    /**
     * Get more histories
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moreHistories(Request $request)
    {
        if($request->ajax()){
            $user = Auth::user();
            $histories = GsClubH::where('user_id', $user->id)->paginate(10);
            $total_his = $histories->count();
            $cur = self::$cur;
            $view = view('user.gsclub.table_tr', compact('histories', 'total_his', 'cur'));
            if($view){
                return ['data'=>"$view"];
            }
            return 0;
        }
    }
    /**
     * Cashout earning to pending wallet
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cashout(Request $request)
    {
        $user = Auth::user();
        $member = GsClub::where([ 
            ['user_id', $user->id], 
            ['status', 0]   
        ])->first();
        if($member){
            $amt = $member->wbal;
            $vat = (7.5/100)*$amt;
            $fee = (7/100)*$amt;
            $hamt = (1.5/100)*$amt;
            $assoc_amt = (1.5/100)*$amt;
            $h_token = $hamt / self::$h_token_price;
            if($amt >= 1000){
                $amt -= $vat;
                $amt -= $fee;
                $amt -= $hamt;
                $amt -= $assoc_amt;
                $member->wbal = 0;
                $member->save();
                self::shareCommision($user->ref_gnum, $amt, $user->placed_by);
                $refAmt = (array_sum($this->formular) / 100)*$amt;
                $amt = $amt - $refAmt;
                $user->self::$pend_balance += $amt;
                $user->self::$h_token += $h_token;
                $user->save();
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$user->id,
                    'amount'=>$vat,
                    'type'=>2,
                    'description'=>self::$cur.number_format($vat)." VAT fee"
                ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$user->id,
                    'amount'=>$fee,
                    'type'=>3,
                    'description'=>self::$cur.number_format($fee)."Processing fee"
                ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$user->id,
                    'amount'=>$hamt,
                    'type'=>4,
                    'description'=>self::$cur.number_format($hamt)."Health insurance"
                ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$user->id,
                    'amount'=>$amt,
                    'type'=>5,
                    'description'=>self::$cur.number_format($amt)." Sent to P-Wallet"
                ]);
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$amt,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$pend_balance,
                    'type'=>'credit',
                    'description'=>self::$cur.number_format($amt).' GSTeam cashout'
                ]);
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$h_token,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$h_token,
                    'type'=>'credit',
                    'description'=>self::$cur.number_format($h_token).' GSTeam cashout'
                ]);
                return back()->with('success', 'Cashout Successfull');
            }else{
                return back()->with('error', 'You can only cashout a minimum of '
                .self::$cur.'1,000');
            }
        }else{
            return back()->with('error', 'You are not a member of GSTeam');
        }
    }
    /**
     * Cashout earning to pending wallet
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Void
    */
    public static function shareCommision($gnumber, $amt, $placed_by, $level=0)
    {
        if($level >= 3)return;
        $reward = ($this->formular[$level] / 100) * $amt;
        $hamt = (1.5/100)*$reward;
        $assoc_amt = (1.5/100)*$reward;
        $reward -= $hamt;
        $reward -= $assoc_amt;
        $h_token = $hamt / self::$h_token_price;
        if($placed_by){
            $user = User::where('gnumber', $placed_by)->first();
            if($user){
                $this->finishCredit($user, $reward, $h_token);
                return self::shareCommision($user->ref_gnum, $amt, $user->placed_by, $level+1);
            }
        }
        $user = User::where('gnumber', $gnumber)->first();
        if($user){
            $this->finishCredit($user, $reward, $h_token);
        }
        return self::shareCommision($user->ref_gnum, $amt, $user->placed_by, $level+1);
    }

    public function finishCredit(User $user, $reward, $h_token)
    {
        $user->self::$pend_balance += $reward;
        $user->self::$h_token += $h_token;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$h_token,
            'gnumber'=>$user->gnumber,
            'name'=>self::$h_token,
            'type'=>'credit',
            'description'=>self::$cur.number_format($h_token).' GSTeam level '.
            $level+1 . ' ref commision'
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$reward,
            'gnumber'=>$user->gnumber,
            'name'=>self::$pend_balance,
            'type'=>'credit',
            'description'=>self::$cur.number_format($reward).' GSTeam level '.
            $level+1 . ' ref commision'
        ]);

    }

}

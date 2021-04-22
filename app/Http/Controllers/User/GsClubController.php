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
        $histories = GsClubH::where('user_id', $user->id)->latest()->paginate(10);
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
        $h_token_price = Helpers::getRegData('h_token_price');
        $h_token_percent = Helpers::getRegData('gs_h_token_percent');
        $fee_percent = Helpers::getRegData('gs_fee_percent');
        $assoc_percent = Helpers::getRegData('gs_assoc_percent');
        $vat_percent = Helpers::getRegData('vat');
        $min_cashout = Helpers::getRegData('gs_min_cashout');
        $formular = explode(',', Helpers::getRegData('gs_ref_com_percent'));
        $user = Auth::user();
        $member = GsClub::where([ 
            ['user_id', $user->id], 
            ['status', 0]   
        ])->first();
        if($member){
            $amt = $member->wbal;
            // $vat = ($vat_percent/100)*$amt;
            $fee = ($fee_percent/100)*$amt;
            $health_amt = ($h_token_percent/100)*$amt;
            $assoc_amt = ($assoc_percent/100)*$amt;
            $h_token = $health_amt / $h_token_price;
            if($amt >= $min_cashout){ 
                // $amt -= $vat;
                $amt -= $fee;
                $amt -= $health_amt;
                $amt -= $assoc_amt;
                $member->wbal = 0;
                $member->save();

                if($user->ref_gnum){
                    self::shareCommision($user, $amt);
                    $refAmt = (array_sum($formular) / 100)*$amt;
                    $amt = $amt - $refAmt;
                }
                $user->pend_balance += $amt;
                $user->h_token += $h_token;
                $user->save();
                // GsClubH::create([
                //     'id'=>Helpers::genTableId(GsClubH::class),
                //     'user_id'=>$user->id,
                //     'amount'=>$vat,
                //     'type'=>2,
                //     'description'=>self::$cur.number_format($vat)." VAT fee"
                // ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$user->id,
                    'amount'=>$fee,
                    'type'=>3,
                    'description'=>self::$cur.number_format($fee, 2, '.', ',')."Processing fee"
                ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$user->id,
                    'amount'=>$health_amt,
                    'type'=>4,
                    'description'=>self::$cur.number_format($health_amt, 2, '.', ',')."Health insurance"
                ]);
                GsClubH::create([
                    'id'=>Helpers::genTableId(GsClubH::class),
                    'user_id'=>$user->id,
                    'amount'=>$amt,
                    'type'=>5,
                    'description'=>self::$cur.number_format($amt, 2, '.', ',')." Sent to P-Wallet"
                ]);
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$amt,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$pend_balance,
                    'type'=>'credit',
                    'description'=>self::$cur.number_format($amt, 2, '.', ',').' GSTeam cashout'
                ]);
                WalletHistory::create([
                    'id'=>Helpers::genTableId(WalletHistory::class),
                    'user_id'=>$user->id,
                    'amount'=>$h_token,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::$h_token,
                    'type'=>'credit',
                    'description'=>$h_token.' GSTeam cashout'
                ]);
                return back()->with('success', 'Cashout Successfull');
            }else{
                return back()->with('error', 'You can only cashout a minimum of '
                .self::$cur.number_format($min_cashout));
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
    private static function shareCommision(User $user, $amt, $level=0)
    {
        $h_token_price = Helpers::getRegData('h_token_price');
        $h_token_percent = Helpers::getRegData('gs_h_token_percent');
        $assoc_percent = Helpers::getRegData('gs_assoc_percent');

        $formular = explode(',', Helpers::getRegData('gs_ref_com_percent')); 
        if( $level >= count($formular) )return;
        $reward = ((float)$formular[$level] / 100) * $amt;
        
        $health_amt = ($h_token_percent / 100)*$reward;
        $assoc_amt = ($assoc_percent / 100)*$reward;
        $reward -= $health_amt;
        $reward -= $assoc_amt;
        $h_token = $health_amt / $h_token_price;
        
        if($user->placed_by)
            $user = User::where('gnumber', $user->placed_by)->first();
        else    
            $user = User::where('gnumber', $user->ref_gnum)->first();
        if($user){
            self::finishCredit($user, $reward, $h_token, $level+1);
            if($user->ref_gnum)
                self::shareCommision($user, $amt, $level+1);
        }
    }
    private static function finishCredit(User $user, $reward, $h_token, $level=0)
    {
        if($reward <= 0 || $h_token <= 0)return;
        $user->pend_balance += $reward;
        $user->h_token += $h_token;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$h_token,
            'gnumber'=>$user->gnumber,
            'name'=>self::$h_token,
            'type'=>'credit',
            'description'=>$h_token.' GSTeam '.
            Helpers::ordinal($level).' Gen ref commision'
        ]);
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$reward,
            'gnumber'=>$user->gnumber,
            'name'=>self::$pend_balance,
            'type'=>'credit',
            'description'=>self::$cur.number_format($reward).' GSTeam '.
            Helpers::ordinal($level).' Gen ref commision'
        ]);
    }
    public function wheel()
    {
        $user = Auth::user();
        $member = GsClub::where([ 
            ['user_id', $user->id], 
            ['status', 0]   
        ])->first();
        if($member){
            $members = GsClub::where([ 
                ['gbal', $member->gbal],
                ['g', $member->g],
                ['status', 0]   
            ])->orderBy('created_at', 'ASC')->paginate(10);
        }else{
            return back()->with('error', "You are not part of Gsteam wheel");
        }
        return view('user.gsclub.wheel', compact('members'));
    }

}

<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\Partner;
use App\Models\PContract;
use App\Models\PCashout;
use App\Models\WalletHistory;
class PartnershipController extends G360
{
    /**
    * Creates a new controller instance
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(!Auth::user()->partner)
                return redirect(route('user.dashboard.index'))->with('error', "You don't have permission to visit that resource");
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $partner = $user->partner;
        $contracts = PContract::where('partner_id', $partner->id)->latest()->paginate(10);
        return view('user.partner.index', compact('contracts', 'partner'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cashout(Request $request)
    {
        $amount = $request->amount;
        $user = Auth::user();
        $partner = $user->partner;
        if($amount >= $partner->min_with){
            if($partner->balance >= $amount){
                $partner->balance -= $amount;
                if($partner->type == 1){
                    $partner->debited += $amount;
                    $user->self::$with_balance += $amount;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$amount,
                        'gnumber'=>$user->gnumber,
                        'name'=>self::$with_balance,
                        'type'=>'credit',
                        'description'=>self::$cur.number_format($amount).' from partnership earning'
                    ]);
                    $status = 1;
                }else{
                    $status = 0;
                }
                $partner->save();
                PCashout::create([
                    'id'=>Helpers::genTableId(PCashout::class),
                    'partner_id'=>$partner->id,
                    'user_id'=>$user->id,
                    'amount'=>$amount,
                    'status'=>$status
                ]);
                return back()->with('success', 'Payout submitted');
            }else{
                return back()->with('error', 'No enough fund in your balance yet');
            }
        }else{
            return back()->with('error', 'minimum withdrawal amount is '.
            self::$cur.$partner->min_with);
        }
    }
}

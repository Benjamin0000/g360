<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\Package;
use App\Models\Epin;

class EpinController extends Controller
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
        $packages = Package::all();
        return view('user.epin.index', compact('packages'));
    }
    /**
     * Show the buy epin page
     *
     * @return \Illuminate\Http\Response
     */
    public function buy()
    {
        $packages = Package::all();
        return view('user.epin.buy', compact('packages'));
    }
    /**
     * Proccess the buy request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function issueBuy(Request $request)
    {
        $number = (int)$request->number;
        $pkg = (int)$request->package;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $user = Auth::user();

        if(!$number || $number <= 0)
            return back()->with('error', 'Please enter the number of e-pins you wan\'t to buy');
        if(!$pkg || $pkg <= 0)
            return back();

        $package = Package::find($pkg);
        if(!$package)
            return back()->with('error', 'invalid package');

        $total = $number*$package->amount;
        
        if($user->trx_balance > $total){
            $user->trx_balance-=$total;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'amount'=>$total,
                'user_id'=>$user->id,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_wallet',
                'type'=>'debit',
                'description'=>$cur.$total.' debited for '.ucfirst($package->name).
                ' E-pin purchase' 
            ]);
            for($i=0; $i < $number; ++$i){
                Epin::create([
                    'id'=>Helpers::genTableId(Epin::class), 
                    'pkg_id'=>$package->id,
                    'code'=>Helpers::genEpin(),
                    'user_id'=>$user->id,
                    'gnumber'=>$user->gnumber,
                ]);
            }
            return redirect(route('user.epin.show', $package->name))
            ->with('success', 'E-pin has been purchased successfully');
        }
        return back()->with('error', 'Insufficent fund in your T-wallet for this epin purchase');
    }
    /**
     * Display all the purched e-pins
     *
     * @param  string  $pkg (package name)
     * @return \Illuminate\Http\Response
     */
    public function show($pkg)
    {
       if($package = Package::where('name', $pkg)->first()){
            $epins = Epin::where([
                ['user_id', Auth::id()],
                ['pkg_id', $package->id]
            ]);
            $total = $epins->count();
            // $epins = $epins->paginate(10);
            $epins = $epins->get();
            $pkg_name = ucfirst($package->name);
            return view('user.epin.show', compact('epins', 'pkg_name', 'total'));
       }
       return back()->with('error', 'Page not found');
    }
}

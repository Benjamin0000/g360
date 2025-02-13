<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\TrackFreeUser;
use App\Models\WalletHistory;
use App\Models\Package;
use App\Models\Epin;
use Carbon\Carbon;

class PackageController extends Controller
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
     * Display the select package page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $no_pkg = 0;
        $user = Auth::user();
        if($user->pkg_id > $no_pkg)
            return redirect(route('user.dashboard.index'))
            ->with('error', 'Sorry you can\'t access that page');
        return view('user.package.index');
    }
    /**
     * Display the premium packages page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showPremiumPackages()
    {  
        $last_pkg = 7;
        $user = Auth::user();
        if($user->pkg_id == $last_pkg)
            return redirect(route('user.dashboard.index'))
            ->with('error', 'Sorry you can\'t access that page');
        $packages = Package::where('name', '<>', 'free')->get();
        return view('user.package.premium', compact('packages'));
    }
    /**
     * Issue the Free package selection
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selectFreePackage(Request $request)
    {
        $no_pkg = 0;
        $package = Package::where('name', 'free')->first();
        if(!$package)
            return back()->with('error', 'free package not available at the moment');
        $user = Auth::user();
        if($user->pkg_id == $no_pkg){
            TrackFreeUser::create([
                'id'=>Helpers::genTableId(TrackFreeUser::class),
                'user_id'=>$user->id,
                'gnumber'=>$user->gnumber,
            ]);
            $user->pkg_id = $package->id;
            $user->save();
            return redirect(route('user.dashboard.index'))
            ->with('success', 'Free package has been activated'); 
        }
        return redirect(route('user.dashboard.index'))
        ->with('error', 'Sorry you can\'t access that page'); 
    }
    /**
     * Issue the Premium package selection
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function selectPremiumPackage(Request $request)
    {
        if(!$request->ajax())return;
        if(!$request->h || 
           !$request->p || 
           strlen($request->p) != 3 ||
           !in_array($request->h, ['yes', 'no'])
        )return['msg'=>'Can\'t process request at the moment'];

        if(!$request->pay_method)
            return['msg'=>'Please select a payment method'];
        
        $free_pkg_id = 1;
        $last_pkg_id = 7;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $trx_balance = Helpers::TRX_BALANCE;
        $pkg_balance = Helpers::PKG_BALANCE;
        $user = Auth::user();
        $fee = 0;

        #check selected package & make sure not free
        $id = substr($request->p, 2, 1);
        $package = Package::find($id);

        if(!$package || $package->id == $free_pkg_id) 
            return ['msg'=>'Invalid package'];

        if($user->pkg_id == $last_pkg_id)
            return ['msg'=>'You are already in the the last package'];

        if($user->pkg_id == $package->id)
            return ['msg'=>'You are already in this package'];
            
        if($package->id < $user->pkg_id)
            return ['msg'=>'You can\'t downgrade to a lower package'];
        
        $prev_package = Package::find($user->pkg_id);
        if($prev_package)
            $amount = $package->amount - $prev_package->amount;
        else 
            $amount = $package->amount;

        #check for fee
        if($percent = $user->free_t_fee)
            $fee = ($percent/100)*$amount;

        switch($request->pay_method){
            case 'e-pin':
                $pay_method = 'E-pin';
                if(!$code = $request->epin)
                    return ['msg'=>'Please enter an e-pin'];
                $epin = Epin::where([ ['code', $code], ['status', 0] ])->first();
                if($epin){
                    if($epin->pkg_id == $package->id){
                        if($fee){
                            $user->free_t_fee = 0; #clear fee
                            if($user->$trx_balance < $fee){
                                if($user->$pkg_balance < $fee)
                                    return ['msg'=>'Insufficient fund for your 20% package fee'];
                                else{
                                    $user->$pkg_balance-=$fee;
                                    $user->save();
                                    WalletHistory::create([
                                        'id'=>Helpers::genTableId(WalletHistory::class),
                                        'user_id'=>$user->id,
                                        'amount'=>$fee,
                                        'gnumber'=>$user->gnumber,
                                        'name'=>$pkg_balance,
                                        'type'=>'debit',
                                        'description'=>$cur.number_format($fee).' Debited for '
                                        .ucfirst($package->name).' package '.$percent.'% fee'
                                    ]);
                                }
                            }else{
                                $user->$trx_balance-=$fee;
                                $user->save();
                                WalletHistory::create([
                                    'id'=>Helpers::genTableId(WalletHistory::class),
                                    'user_id'=>$user->id,
                                    'amount'=>$fee,
                                    'gnumber'=>$user->gnumber,
                                    'name'=>$trx_balance,
                                    'type'=>'debit',
                                    'description'=>$cur.number_format($fee).' Debited for '
                                    .ucfirst($package->name).' package '.$percent.'% fee'
                                ]);
                            }
                        }
                        $epin->status = 1;
                        $epin->used_by = $user->id;
                        $epin->used_date = Carbon::now();
                        $epin->save();
                    }else
                        return ['msg'=>'E-pin not compatible with this package'];
                }else
                    return ['msg'=>'E-pin does not exist or may have been used.'];
            break;
            case 'trx_w': 
                $pay_method = 'Transaction wallet';
                if($user->$trx_balance >= $amount){
                    if($fee){
                        $total = $amount+$fee;
                        if($user->$trx_balance < $total){
                            if($user->$pkg_balance < $fee)
                                return ['msg'=>'Insufficient fund for your 20% package fee'];
                            else{
                                $user->$pkg_balance-=$fee;
                                $user->save();
                                WalletHistory::create([
                                    'id'=>Helpers::genTableId(WalletHistory::class),
                                    'user_id'=>$user->id,
                                    'amount'=>$fee,
                                    'gnumber'=>$user->gnumber,
                                    'name'=>$pkg_balance,
                                    'type'=>'debit',
                                    'description'=>$cur.number_format($fee).' Debited for '
                                    .ucfirst($package->name).' package '.$percent.'% fee'
                                ]);
                            }
                        }else{
                            $user->$trx_balance-=$fee;
                            $user->save();
                            WalletHistory::create([
                                'id'=>Helpers::genTableId(WalletHistory::class),
                                'user_id'=>$user->id,
                                'amount'=>$fee,
                                'gnumber'=>$user->gnumber,
                                'name'=>$trx_balance,
                                'type'=>'debit',
                                'description'=>$cur.number_format($fee).' Debited for '
                                .ucfirst($package->name).' package '.$percent.'% fee'
                            ]);
                        }
                    }
                    $user->free_t_fee = 0; #clear fee
                    $user->$trx_balance-=$amount;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$amount,
                        'gnumber'=>$user->gnumber,
                        'name'=>$trx_balance,
                        'type'=>'debit',
                        'description'=>$cur.number_format($amount).' Debited for '
                        .ucfirst($package->name).' package'
                    ]);
                }else
                    return ['msg'=>'Insufficient fund in your transaction wallet for the '.
                    ucfirst($package->name).' package'];
            break;
            default: 
                return ['msg'=>'Invalid package'];
        }
        $done = $package->activate($user, $request->h, $pay_method);
        if($done){
            $request->session()->flash('pkg_activated', ucfirst($package->name).
            ' package has been activated successfully');
            return ['status'=>1, 'msg'=>'package successful'];
        }else{
            return ['msg'=>'package could not be activated'];
        }
    }
    public function upgrade()
    {
        $ripe = Helpers::ripeForUpgrade();
        if($ripe){
            $package = Package::where('amount', $ripe)->first();
            return view('user.package.upgrade', compact('package'));
        }
        return back();
    }
    public function autoUpgrade(Request $request)
    {   
        $this->validate($request, [
            'h'=>['required']
        ]);
        if($ripe = Helpers::ripeForUpgrade()){
            $cur = Helpers::LOCAL_CURR_SYMBOL;
            $user = Auth::user();
            $package = Package::where('amount', $ripe)->first();
            $amount = $package->amount - $user->package->amount;
            $user->pkg_balance -= $amount;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$amount,
                'gnumber'=>$user->gnumber,
                'name'=>'pkg_balance',
                'type'=>'debit',
                'description'=>$cur.$amount.' Debited for '.ucfirst($package->name).' package'
            ]);
            if($package->activate($user, $request->h, 'pkg_balance')){
                return redirect(route('user.dashboard.index'))->with('success', ucwords($package->name).' Package activated');
            }
        }
        return back();
    }

}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.package.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPremiumPackages()
    {
        $packages = Package::where('name', '<>', 'free')->get();
        return view('user.package.premium', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selectFreePackage(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selectPremiumPackage(Request $request)
    {
        if(!$request->ajax())return;
        if(!$request->pay_method || 
           !$request->h || 
           !$request->p || 
           strlen($request->p) != 3 ||
           !in_array($request->h, ['yes', 'no'])
        )return['msg'=>'<i class=\'fa fa-info-circle\'></i> Can\'t process request at the moment'];
        #check selected package
        $id = substr($request->p, 2, 1);
        $package = Package::find($id);
        if($id == 1 || !$package) 
        return ['msg'=>'<i class=\'fa fa-info-circle\'></i> Invalid package'];

        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $user = Auth::user();
        $prev_package = Package::find($user->pkg_id);
        if($prev_package)
            $amount = $package->amount - $prev_package->amount;
        else 
            $amount = $package->amount;

        switch($request->pay_method){
            case 'e-pin':
               $pay_method = 'E-pin';
               if(!$code = $request->epin)
                    return ['msg'=>'<i class=\'fa fa-info-circle\'></i> Please enter an e-pin'];
               $epin = Epin::where([ ['code', $code], ['status', 0] ])->first();
               if($epin){
                    if($epin->pkg_id == $package->id){
                        $epin->status = 1;
                        $epin->used_by = $user->id;
                        $epin->used_date = Carbon::now();
                        $epin->save();
                    }else
                        return ['msg'=>'<i class=\'fa fa-info-circle\'></i> E-pin not compatible with this package'];
               }else
                    return ['msg'=>'<i class=\'fa fa-info-circle\'></i> E-pin does not exist or may have been used.'];
            break;
            case 'card': 
                $pay_method = 'Credit or Debit card';
                // process card payment
                return ['msg'=>'<i class=\'fa fa-info-circle\'></i> Card payments not available at the moment'];
            break;
            case 'trx_w': 
                if($user->t_balance > $amount){
                    $pay_method = 'Transaction wallet';
                    $user->t_balance-=$amount;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$amount,
                        'gnumber'=>$user->gnumber,
                        'name'=>'t_balance',
                        'type'=>'debit',
                        'description'=>$cur.number_format($amount).' Debited for '.ucfirst($this->name).' package'
                    ]);
                }else
                    return ['msg'=>'<i class=\'fa fa-info-circle\'></i> Insufficient fund in your transaction wallet for the '.ucfirst($package->name).' package'];
            break;
            case 'pkg_w': 
                if($user->pkg_balance > $amount){
                    $pay_method = 'Package wallet';
                    $user->pkg_balance-=$amount;
                    $user->save();
                    WalletHistory::create([
                        'id'=>Helpers::genTableId(WalletHistory::class),
                        'user_id'=>$user->id,
                        'amount'=>$amount,
                        'gnumber'=>$user->gnumber,
                        'name'=>'pkg_balance',
                        'type'=>'debit',
                        'description'=>$cur.number_format($amount).' Debited for '.ucfirst($this->name).' package'
                    ]);
                }else 
                    return ['msg'=>'<i class=\'fa fa-info-circle\'></i> Insufficient fund in your package wallet for the '.ucfirst($package->name).' package'];
            break;
            default: 
                return ['msg'=>'<i class=\'fa fa-info-circle\'></i> Invalid package'];
        }
        $done = $package->activate($request->h, $pay_method);
        if($done){
            $request->session()->flash('pkg_activated', '<i class=\'fa fa-check-circle\'></i> '.ucfirst($package->name).' package has been activated successfully');
            return ['status'=>1, 'msg'=>'package successful'];
        }else{
            return ['msg'=>'<i class=\'fa fa-info-circle\'></i> package could not be activated'];
        }
    }

}

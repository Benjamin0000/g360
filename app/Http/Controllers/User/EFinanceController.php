<?php
namespace App\Http\Controllers\User;
use App\Http\Helpers;
use App\Http\Controllers\G360;
use App\Lib\Epayment\Airtime as Pairtime;
use App\Lib\Epayment\Data as DataSubscription;
use App\Lib\Epayment\Electricity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use App\Models\Airtime;
use App\Models\DataSub;
use App\Models\FAccount;
use App\Models\EDisco;
class EFinanceController extends G360
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
        if(!$user->faccount){
            FAccount::create([
                'id'=>Helpers::genTableId(FAccount::class),
                'user_id'=>$user->id
            ]);
        }
        return view('user.e_finance.index');
    }
    /**
     * Show electrical pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function electricity()
    {
        $discos = EDisco::all();
        return view('user.e_finance.pay_bills.electricity.index', compact('discos'));
    }
    public function buyMeterUnit(Request $request)
    {
        $this->validate($request, [
            'disco'=>['required'],
            'meter_number'=>['required', 'numeric'],
            'amount'=>['required', 'numeric']
        ]);
        $elect = new Electricity($request->meter_number, $request->disco, $request->amount);
        switch($request->type)
        {
            case 1: 
                $data = $elect->validateMeter();
                if(isset($data['error']))
                    return $data;
                $data['amt'] = $request->amount;
                $data['service'] = $request->disco;
                $view = view('user.e_finance.pay_bills.electricity.info', compact('data'));
                return ['status'=>"$view"];
            break;
            case 2:
                
                $data = $elect->purchase();
            default: 
                return ['error'=>'invalid operation'];
        }
    }
    /**
     * Show airtime/data pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function airtimeData()
    {
        $airtimes = Airtime::all();
        $datasub = DataSub::all();
        return view('user.e_finance.pay_bills.airtime_data.index', compact('airtimes', 'datasub'));
    }
    public function buyAirtime(Request $request)
    {
        if(!$request->ajax()) return;
        $this->validate($request, [
            'mobile_number'=>['required', 'numeric'],
            'amount'=>['required', 'numeric']
        ]);
        if(!$request->operator){
            return ['error'=>'Select a network provider'];
        }
        $airtime = Airtime::where('name', $request->operator)->first();
        if(!$airtime){
            return ['error'=>'Select a network provider'];
        }
        $amt = $request->amount;
        if($amt < $airtime->min_buy){
            return ['error'=>'Minimum is '.$airtime->min_buy.' naira'];
        }
        if($amt > $airtime->max_buy){
            return ['error'=>'Maximum is '.$airtime->max_buy.' naira'];
        }
        $user = Auth::user();
        if($user->trx_balance < $amt){
            return ['error'=>'Insufficient fund in your TRX-Wallet'];
        }
        $req = new Pairtime();
        $data = $req->validatePhone($request->mobile_number, $request->operator, $amt);
        if(isset($data['error']))
            return ['error'=>'Invalid number for this mobile operator'];
        if($data == true && $req->purchase()){
            $com = $amt*($airtime->comm/100);
            $user->pend_balance += $com;
            $user->faccount->vtu_deca += $com;
            $user->faccount->save();
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$com,
                'gnumber'=>$user->gnumber,
                'name'=>self::$pend_balance,
                'type'=>'credit',
                'description'=>self::$cur.$com.
                ' earned from airtime cashback'
            ]);
            return ['status'=>'success'];
        }
        return ['error'=>'Not available at the moment'];
    }
    public function getDataPlan(Request $request)
    {
        if(!$request->ajax()) return;
        $this->validate($request, [
            'mobile_number'=>['required', 'numeric'],
        ]);
        $mobile = $request->mobile_number;
        $mobile = preg_replace('/^0/','234', $mobile);
        $data = new DataSubscription();
        $products = $data->getDataPlan($mobile)['products'];
        return view('user.e_finance.pay_bills.airtime_data.plan', compact('products'));
    }
    public function purchaseData(Request $request)
    {
        $this->validate($request, [
            'mobile_number'=>['required', 'numeric'],
            'plan'=>['required'],
            'operator'=>['required']
        ]);
        if(!$request->operator){
            return ['error'=>'Select a network provider'];
        }
        $check = DataSub::where('name', $request->operator)->first();
        if(!$check){
            return ['error'=>'Select a network provider'];
        }
        $data = new DataSubscription();
        $plan = explode(',', $request->plan);
        $price = (float)$plan[2];
        $user = Auth::user();
        if($user->trx_balance < $price){ 
            return ['error'=>'Insufficient Fund in your TRX-Wallet'];
        }
        $p = $data->purchase($plan[0], $request->mobile_number, $plan[1], $price, $request->operator, $plan[3]);
        if($p){
            $com = $price*($check->comm/100);
            $user->pend_balance += $com;
            $user->faccount->vtu_deca += $com;
            $user->faccount->save();
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$com,
                'gnumber'=>$user->gnumber,
                'name'=>self::$pend_balance,
                'type'=>'credit',
                'description'=>self::$cur.$com.
                ' earned from data purchase cashback'
            ]);
            return ['status'=>1];
        }else{
            return ['error'=>'Operation could not process'];
        }
    }
    /**
     * Show Cable tv pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function tvSub()
    {
        return view('user.e_finance.pay_bills.tvsub.index');
    }
    /**
     * Show waterSub pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function waterSub()
    {
        return view('user.e_finance.pay_bills.waterSub.index');
    }
}

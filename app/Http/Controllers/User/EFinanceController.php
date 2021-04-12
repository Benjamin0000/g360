<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use App\Lib\Epayment\Airtime as Pairtime;
use App\Lib\Epayment\Data as DataSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Airtime;
use App\Models\DataSub;
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
        return view('user.e_finance.index');
    }
    /**
     * Show electrical pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function electricity()
    {
        return view('user.e_finance.pay_bills.electricity.index');
    }
    public function buyMeterUnit(Request $request)
    {
        $this->validate($request, [
            'disco'=>['required'],
            'meter_number'=>['required', 'numeric'],
            'amount'=>['required', 'numeric']
        ]);
        $url = "";
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
        $check = Airtime::where('name', $request->operator)->exists();
        if(!$check){
            return ['error'=>'Select a network provider'];
        }
        if($request->amount < 50){
            return ['error'=>'Minimum is 50naira'];
        }
        $user = Auth::user();
        if($user->trx_balance < $request->amount){
            return ['error'=>'Insufficient fund in your TRX-Wallet'];
        }
        $req = new Pairtime();
        $data = $req->validatePhone($request->mobile_number, $request->operator, $request->amount);
        if(isset($data['error']))
            return ['error'=>'Invalid number for this mobile operator'];
        if($data == true && $req->purchase()){
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
        $check = DataSub::where('name', $request->operator)->exists();
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

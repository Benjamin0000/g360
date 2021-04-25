<?php
namespace App\Http\Controllers\User;
use App\Http\Helpers;
use App\Http\Controllers\G360;
use App\Lib\Epayment\Airtime as Pairtime;
use App\Lib\Epayment\Data as DataSubscription;
use App\Lib\Epayment\Electricity;
use App\Lib\Epayment\CableTv as ECableTv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use App\Models\Airtime;
use App\Models\DataSub;
use App\Models\FAccount;
use App\Models\EDisco;
use App\Models\CableTv;
use App\Models\VtuTrx;
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
        if(!FAccount::where('user_id', $user->id)->exists()){
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
        $user = Auth::user();
        $discos = EDisco::all();
        $histories = VtuTrx::where([
            ['user_id', $user->id],
            ['type', 'electricity']
        ])->latest()->paginate(10);
        return view('user.e_finance.pay_bills.electricity.index', 
        compact('discos', 'histories'));
    }
    /**
     * Issue Purchase meter unit request
     *
     * @return \Illuminate\Http\Response
    */
    public function buyMeterUnit(Request $request)
    {
        $this->validate($request, [
            'disco'=>['required'],
            'meter_number'=>['required', 'numeric'],
            'amount'=>['required', 'numeric']
        ]);
        $amount = $request->amount;
        if($amount < 0)
            return ['error'=>"Invalid amount"];
        if(!$disco = EDisco::where('code', $request->disco)->first())
            return ['error'=>"Invalid Disco selected"];
        $elect = new Electricity($request->meter_number, $disco, $amount);
        $user = Auth::user();
        switch($request->type)
        {
            case 1:
                $total = $amount + $disco->charge;
                if($user->trx_balance < $total)
                    return ['error'=>"Insufficient fund"];
                $data = $elect->validateMeter();
                if(isset($data['error']))
                    return $data;
                $data['amt'] = $request->amount;
                $data['service'] = $disco->code;
                $data['charge'] = $disco->charge;
                $view = view('user.e_finance.pay_bills.electricity.info', compact('data'));
                return ['status'=>"$view"];
            break;
            case 2:
                if($elect->purchase())
                    return back()->with('success', 'Transaction completed');
                return back()->with('error', 'Cound not complete transaction');
                break;
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
        $user = Auth::user();
        $airtimes = Airtime::all();
        $datasub = DataSub::all();

        $histories = VtuTrx::where([
            ['user_id', $user->id],
            ['type', 'airtime']
        ])->orWhere('type', 'data')->latest()->paginate(10);

        return view('user.e_finance.pay_bills.airtime_data.index', 
        compact('airtimes', 'datasub', 'histories'));
    }
    /**
     * Issue buy Airtime request
     *
     * @return \Illuminate\Http\Response
     */
    public function buyAirtime(Request $request)
    {
        if(!$request->ajax()) return;
        $this->validate($request, [
            'mobile_number'=>['required', 'numeric'],
            'amount'=>['required', 'numeric']
        ]);
        if(!$request->operator)
            return ['error'=>'Select a network provider'];
        
        $airtime = Airtime::where('name', $request->operator)->first();
        if(!$airtime)
            return ['error'=>'Select a network provider'];
        
        $amt = $request->amount;
        if($amt < $airtime->min_buy)
            return ['error'=>'Minimum is '.self::$cur.$airtime->min_buy]; 
        
        if($amt > $airtime->max_buy)
            return ['error'=>'Maximum is '.self::$cur.$airtime->max_buy];
        
        $user = Auth::user();
        if($user->trx_balance < $amt)
            return ['error'=>'Insufficient fund in your TRX-Wallet'];
        
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
                'description'=>'Airtime Cashback'
            ]);
            #credit upline
            $value = (int)($user->faccount->vtu_deca/50);
            $value - $user->faccount->v_deca_c;
            if($value > 0){
                $req->creditUpline($airtime, $user);
                $user->faccount->v_deca_c += $value;
                $user->faccount->save();
            }
            return ['status'=>'success'];
        }
        return ['error'=>'Not available at the moment'];
    }
    /**
     * Issue get data plan request
     *
     * @return \Illuminate\Http\Response
     */
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
    /**
     * Issue purchase data request
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseData(Request $request)
    {
        $this->validate($request, [
            'mobile_number'=>['required', 'numeric'],
            'plan'=>['required'],
            'operator'=>['required']
        ]);
        if(!$request->operator)
            return ['error'=>'Select a network provider'];
        
        $check = DataSub::where('name', $request->operator)->first();
        if(!$check)
            return ['error'=>'Select a network provider'];
        
        $data = new DataSubscription();
        $plan = explode(',', $request->plan);
        $product = $data->getDataPlanPrice($request->mobile_number, $plan[0]);
        if(isset($product['denomination'])){
            $price = $product['denomination'];
            $product_id = $product['product_id'];
            $data_amt = $product['data_amount'];
            $mobile_number = $request->mobile_number;
            $operator = $request->operator;
            $validity = $product['validity'];
        }else{
            return ['error'=>'Unvailable'];
        }
        $user = Auth::user();
        if($price <= 0)
            return ['error'=>'not available'];
        if($user->trx_balance < $price)
            return ['error'=>'Insufficient Fund in your TRX-Wallet'];
        
        $p = $data->purchase($product_id, $mobile_number, $data_amt, $price, $operator, $validity);
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
                ' data purchase cashback'
            ]);
            #credit upline
            $value = (int)($user->faccount->vtu_deca/50);
            $value - $user->faccount->v_deca_c;
            if($value > 0){
                $data->creditUpline($check, $user);
                $user->faccount->v_deca_c += $value;
                $user->faccount->save();
            }
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
        $user = Auth::user();
        $providers = CableTv::all();
        $histories = VtuTrx::where([
            ['user_id', $user->id],
            ['type', 'cabletv']
        ])->latest()->paginate(10);
        return view('user.e_finance.pay_bills.tvsub.index', 
        compact('providers', 'histories'));
    }
    /**
     * Get Tv plans package
     *
     * @return \Illuminate\Http\Response
     */
    public function tvPlans($p = 0)
    {
        if($provider = CableTv::find($p)){
            $ecable = new ECableTv($provider->code);
            $plans = $ecable->getPriceList()['products'];
            return view('user.e_finance.pay_bills.tvsub.plans', compact('plans'));
        }
        return ["<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Invalid provider</div>"];
    }
    /**
     * Validate Tv account number
     *
     * @return \Illuminate\Http\Response
     */
    public function validateTvAcc(Request $request)
    {
        $this->validate($request, [
            'provider'=>['required', 'numeric'],
            'smart_card'=>['required', 'numeric'],
            'package_code'=>['required']
        ]);
        $user = Auth::user();
        if($provider = CableTv::find($request->provider)){
            $ecable = new ECableTv($provider->code);
            #validate plan
            if($package = $ecable->getPlan($request->package_code)){
                $price = $package['topup_value'] + $provider->charge;
                if($user->trx_balance < $price)
                    return ['error'=>"Insufficient fund for this ".$provider->name." package"];
            }else{
                return ['error'=>'package not available'];
            }
            $data = $ecable->validateSmartCard($request->smart_card);
            if($ecable->sm_card_okay){
                $data['provider'] = $provider;
                $data['package'] = $package;
                $data['smart_card'] = $request->smart_card;
                $value = view('user.e_finance.pay_bills.tvsub.info', compact('data'));
                return ['status'=>"$value"];
            }
            return ['error'=>'Incorrect Smart card number'];
        }
        return ['error'=>'Invalid Provider'];
    }
    /**
     * Issue Tv Subscription request
     *
     * @return \Illuminate\Http\Response
     */
    public function finishSubTv(Request $request)
    {
        $this->validate($request, [
            'service'=>['required'],
            'smartcard_number'=>['required', 'numeric'],
            'product_code'=>['required']
        ]);
        if($provider = CableTv::where('code', $request->service)->first()){
            $ecable = new ECableTv($provider->code);
            $user = Auth::user();
            if($package = $ecable->getPlan($request->product_code)){
                $price = $package['topup_value']+$provider->charge;
                if($user->trx_balance < $price)
                    return back()->with('error', "Insufficient fund for this ".$provider->name." package");
                $ecable->validateSmartCard($request->smartcard_number);
                if($ecable->sm_card_okay){
                    $ecable->purchase($request->smartcard_number, $package, $price);
                    if($ecable->purchase_done)
                        return back()->with('success', "Package activation successful");
                    return back()->with('error', "activation faild"); 
                }else{
                    return back()->with('error', 'Invalid smart card number');
                }
            }else{
                return back()->with('error', 'package not available');
            }
        }
        return back()->with('error', 'Invalid provider');
    }
    /**
     * Banking
     *
     * @return \Illuminate\Http\Response
     */
    public function banking()
    {
        return view('user.e_finance.banking.index');
    }
}

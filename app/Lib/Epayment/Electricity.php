<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\EDisco;
class Electricity
{
    private $bearer;
    public $meter;
    public $service;
    public $amount;
    public function __construct($meter, $service, $amount)
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
       $this->meter = $meter;
       $this->service = $service;
       $this->amount = $amount;
    }
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
    }
    public function validateMeter()
    {
        $url = "http://epayment.com.ng/epayment/api/3pelectricity_validate";
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "service"=>$this->service,
            "customer_reference"=>$this->genRefNo(),
            "meter"=>$this->meter
        ]);
        $data = json_decode($response, true);
        if(isset($data['minAmount'])){
            if($data['minAmount'] > $this->amount)
                return ['error'=>"minimum amount is ".$cur.$data['minAmount']];
            else 
                return $data;
        }else{
            return ['error'=>'unavailable'];
        }
    }
    public function purchase()
    {
        $url = "http://epayment.com.ng/epayment/api/3pelectricity_vend";
        $refCode = $this->genRefNo();
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "service"=>$this->service,
            "customer_reference"=>$refCode,
            "meter"=>$this->meter,
            "amount"=>$this->amount
        ]);
        $data = json_decode($response, true);
        if(isset($data['status']) && $data['status'] == 201){
            VtuTrx::create([
                'id'=>$refCode,
                'user_id'=>Auth::id(),
                'amount'=>$this->amount,
                'type'=>'electricity',
                'service'=>$this->service
            ]);
            $user = Auth::user();
            $user->trx_balance -= $this->amount;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$this->amount,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_balance',
                'type'=>'debit',
                'description'=>'Electricity billing'
            ]);
            return true;
        }
        return false;
    }
    public function creditCashBack()
    {
        $disco = EDisco::where('code', $this->service)->first();
        if($disco){
            $user = Auth::user();
            $user->pend_balance += $disco->comm_amt;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$this->amount,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_balance',
                'type'=>'debit',
                'description'=>'Electricity billing'
            ]);
        }
    }
    public function verifyPurchase($customer_reference)
    {
        $url = "http://epayment.com.ng/epayment/api/3pelectricity_verify";
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "customer_reference"=>$customer_reference,
        ]);
        $data = json_decode($response, true);
    }
}
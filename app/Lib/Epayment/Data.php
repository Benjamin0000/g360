<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
class Data
{
    private $bearer;
    public $product_code;
    public $service;
    public $amount;
    public $phone;
    public function __construct()
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
    }
    public function getDataPlan($number)
    {
        $url = "http://epayment.com.ng/epayment/api/3pbundle_validate";
        $mobile = preg_replace('/^0/','234', $number);
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "phone"=>$mobile,
        ]);
        $data = json_decode($response, true);
        return $data;
    }
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
    }
    public function purchase($code, $phone, $data_amount, $price, $service, $validity)
    {
        $phone = preg_replace('/^0/','234', $phone);
        $url = 'http://epayment.com.ng/epayment/api/3pbundle_vend';
        $refCode = $this->genRefNo();
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "product_code"=>$code,
            "phone"=>$phone,
            "amount"=>$data_amount,
            "customer_reference"=>$refCode,
            "service"=>$service
        ]);
        $data = json_decode($response, true);
        if(isset($data['status']) && $data['status'] == 201){
            VtuTrx::create([
                'id'=>$refCode,
                'user_id'=>Auth::id(),
                'amount'=>$price,
                'type'=>'data',
                'service'=>$service,
                'description'=>$data_amount.'MB'.$validity.' Mobile data purchase'
            ]);
            $user = Auth::user();
            $user->trx_balance -= $price;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$price,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_balance',
                'type'=>'debit',
                'description'=>'Mobile data purchase'
            ]);
            return true;
        }
        return false;
    }
    public function verifyPurchase($customer_reference)
    {
        $url = 'http://epayment.com.ng/epayment/api/3pbundle_verify';
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "customer_reference"=>$customer_reference,
        ]);
        $data = json_decode($response, true);
        if(isset($data['status']) && $data['status'] == 200)
            return true;
        return false;
    }
}
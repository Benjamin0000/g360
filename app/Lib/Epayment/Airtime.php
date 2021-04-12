<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
class Airtime
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
    public function validatePhone($number, $operator, $amount)
    {
        $url = 'http://epayment.com.ng/epayment/api/3pvtu_validate';
        $mobile = preg_replace('/^0/','234', $number);
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "phone"=>$mobile,
        ]);
        $data = json_decode($response, true);
        if(isset($data['opts']) && isset($data['opts']['operator'])){
            if(strtolower($data['opts']['operator']) == strtolower($operator)){
                if( isset($data['products']) &&
                    isset($data['products'][0]) &&
                    isset($data['products'][0]['product_id'])
                ){
                    $this->product_code = $data['products'][0]['product_id'];
                    $this->service = $data['opts']['operator'];
                    $this->amount = $amount;
                    $this->phone = $mobile;
                    return true;
                }
            }else{
                return ['error'=>'operator not match'];
            }
        }
        return false;
    }
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
    }
    public function purchase()
    {
        $url = 'http://epayment.com.ng/epayment/api/3pvtu_vend';
        $refCode = $this->genRefNo();
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "product_code"=>$this->product_code,
            "phone"=>$this->phone,
            "amount"=>$this->amount,
            "customer_reference"=>$refCode,
            "service"=>$this->service
        ]);
        $data = json_decode($response, true);
        if(isset($data['status']) && $data['status'] == 201){
            VtuTrx::create([
                'id'=>$refCode,
                'user_id'=>Auth::id(),
                'amount'=>$this->amount,
                'type'=>'airtime',
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
                'description'=>'airtime purchase'
            ]);
            return true;
        }
        return false;
    }
    public function verifyPurchase($customer_reference)
    {
        $url = 'http://epayment.com.ng/epayment/api/3pvtu_verify';
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "customer_reference"=>$customer_reference,
        ]);
        $data = json_decode($response, true);
        return $data;
    }
}

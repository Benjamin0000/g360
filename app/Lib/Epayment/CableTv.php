<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\User;
class CableTv
{
    private $bearer;
    public $sc_number;
    public $service;
    public $product_code;
    public $proceed = false;
    public function __construct($service)
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
       $this->service = $service;
    }
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
    }
    public function getPriceList()
    {
        $url = "http://epayment.com.ng/epayment/api/3pcable_prices";
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "service"=>$this->service,
        ]);
        return json_decode($response, true);
    }
    public function validateSmartCard($number)
    {
        $url = "http://epayment.com.ng/epayment/api/3pcable_validate";
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "service"=>$this->service,
            'smartcard_number'=>$number
        ]);
        $data = json_decode($response, true);
        if(isset($data['name'])){
            $this->proceed = true;
            return $data;
        }
        return false;
    }
    public function purchase()
    {

    }
    public function creditCashBack(EDisco $disco)
    {

    }
     /**
     * Verify utility bills payment
     *
     * @return bollean
     */ 
    public function verifyPurchase($customer_reference)
    {

    }
    /**
     * Share referral commissions
     *
     * @return null
    */ 
    public function creditUpline(EDisco $disco, User $user, $level = 1)
    {

    }
}
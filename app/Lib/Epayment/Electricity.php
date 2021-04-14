<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
class Electricity
{
    private $bearer;
    public $product_code;
    public $service;
    public $amount;
    public function __construct()
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
    }
    public function validateMeter($number, $operator, $amount)
    {

    }
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
    }
    public function purchase()
    {

    }
    public function verifyPurchase($customer_reference)
    {

    }
}
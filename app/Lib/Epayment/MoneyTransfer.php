<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\Bank;
class Airtime
{
    private $bearer;

    public function __construct()
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
    }
    public function getAccountInfo($number, $id)
    {
        $bank = Bank::find($id);
        $url = 'http://epayment.com.ng/epayment/api/3pbank_validate';
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "accountnumber"=>$number,
            "bankcode"=>$bank->code
        ]);
        $data = json_decode($response, true);
        if( isset($data['status']) && $data['status'] == 200 ){
            if(isset($data['name']))
                return $data;
        }
        return false;
    }
    public function finishTransfer()
    {

    }
    public function verifyTransfer()
    {

    }
}
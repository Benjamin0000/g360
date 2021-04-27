<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\Bank;
class MoneyTransfer
{
    private $bearer;

    public function __construct()
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
    }
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
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
        return $data = json_decode($response, true);
    }
    public function fundTransfer($number, $id, $amount)
    {
        $user = Auth::user();
        $bank = Bank::find($id);
        
        $url = 'http://epayment.com.ng/epayment/api/3pbank_transfer';
        $refCode = $this->genRefNo();
        $beneficiary = $this->getAccountInfo($number, $bank->id);
        if(!$beneficiary) return false;

        if($user->with_balance < $amount)
            return false;

        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "accountnumber"=>$number,
            "amount"=>$amount,
            "customer_reference"=>$refCode,
            "bankcode"=>$bank->code
        ]);
        $data = json_decode($response, true);
        if(isset($data['status']) && strtolower($data['status']) == 'successful'){
            VtuTrx::create([
                'id'=>$refCode,
                'user_id'=>$user->id,
                'amount'=>$amount,
                'type'=>'transfer',
                'service'=>$bank->name,
                'description'=>'Fund transfer to '.$bank->name.
                ' '.$beneficiary['name'].' [ '.$number.' ]'
            ]);
            $user->with_balance -= $amount;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$amount,
                'gnumber'=>$user->gnumber,
                'name'=>'with_balance',
                'type'=>'debit',
                'description'=>'Fund transfer to '.$bank->name.
                ' '.$beneficiary['name'].' [ '.$number.' ]'
            ]);
            return true;
        }
        return false;
    }
    public function verifyTransfer()
    {

    }
}
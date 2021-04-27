<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\Airtime as RCard;
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
    /**
     * Validate mobile number by network provider
     * @param $number Int
     * @param $perator String 
     * @param $amount Float airtime amout
     * @return \Illuminate\Http\Response
     */
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
        if( isset( $data['opts'] ) && isset( $data['opts']['operator'] ) ){
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
                return ['error'=>'operator don\'t match'];
            }
        }
        return false;
    }
    /**
     * Generate Reference number
     *
     * @return \Illuminate\Http\Response
     */
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
    }
    /**
     * Issue airtime purchase
     *
     * @return \Illuminate\Http\Response
     */
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
            $user = Auth::user();
            $mobile = '0'.substr($this->phone, 3, 15);
            VtuTrx::create([
                'id'=>$refCode,
                'user_id'=>$user->id,
                'amount'=>$this->amount,
                'type'=>'airtime',
                'service'=>$this->service,
                'description'=>'['.$mobile.']'
            ]);
            $user->trx_balance -= $this->amount;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$this->amount,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_balance',
                'type'=>'debit',
                'description'=>$this->service.' Airtime Purchase ['.$mobile.']'
            ]);
            return true;
        }
        return false;
    }
    /**
     * Verify Airtime purchase
     *
     * @return \Illuminate\Http\Response
     */
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
     /**
     * Share referral commissions
     *
     * @return null
    */ 
    public function creditUpline(RCard $airtime, User $user, $level = 1)
    {
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $formular = json_decode('[' . $airtime->ref_amt . ']', true);
        $levels = count($formular);
        if($level > $levels) return;
        if($user->placed_by)
            $user = User::where([ ['gnumber', $user->placed_by], ['status', 1] ])->first();
        else
            $user = User::where([ ['gnumber', $user->ref_gnum], ['status', 1] ])->first();
        $amt = (float)$formular[$level - 1];
        $user->pend_balance += $amt;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'amount'=>$amt,
            'user_id'=>$user->id,
            'gnumber'=>$user->gnumber,
            'name'=>'pend_balance',
            'type'=>'credit',
            'description'=>"Airtime ".Helpers::ordinal($level)." Gen Airtime referral commision" 
        ]);
        $user->faccount->deca += $amt;
        $user->faccount->save();
        if($user->ref_gnum)
            $this->creditUpline($airtime, $user, $level+1);
        else 
            return;
    }
}

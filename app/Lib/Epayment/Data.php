<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\DataSub;
class Data
{
    private $bearer;

    public function __construct()
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
    }
    /**
     * Issue get data plan request
     * @param $number Int
     * @return array
     */
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
    /**
     * Generate reference no
     * 
     * @return string
     */
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = VtuTrx::find($code);
        return $check ? $this->genRefNo() : $code;
    }
    /**
     * Issue data plan price request
     * @param $number INT
     * @param $code String
     * @return array || boolean
     */
    public function getDataPlanPrice($number, $code)
    {
        $products = $this->getDataPlan($number)['products'];
        foreach($products as $product){
            if($product['product_id'] == $code)
                return $product;
        }
        return false;
    }
    /**
     * Issue purchase data plan request
     * 
     * @return string
     */
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
            $user = Auth::user();
            VtuTrx::create([
                'id'=>$refCode,
                'user_id'=>$user->id,
                'amount'=>$price,
                'type'=>'data',
                'service'=>$service,
                'description'=>$data_amount.'MB '.$validity.' Mobile data purchase'
            ]);
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
    /**
     * Verify Data plan purchase
     * 
     * @return string
     */
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
    /**
     * Share referral commissions
     *
     * @return null
    */ 
    public function creditUpline(DataSub $data, User $user, $level = 1)
    {
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $formular = json_decode('[' . $data->ref_amt . ']', true);
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
            'description'=>Helpers::ordinal($level)." Gen Data sub. referral commision" 
        ]);
        $user->faccount->deca += $amt;
        $user->faccount->save();
        if($user->ref_gnum)
            $this->creditUpline($data, $user, $level+1);
        else 
            return;
    }
}
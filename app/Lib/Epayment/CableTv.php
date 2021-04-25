<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\User;
use App\Models\CableTv as ECableTv;
class CableTv
{
    private $bearer;
    public $service;
    public $sm_card_okay = false;
    public $purchase_done = false;
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
            $this->sm_card_okay = true;
            return $data;
        }
        return false;
    }
    public function getPlan($code)
    {
        $lists = $this->getPriceList();
        if(isset($lists['products'])){
            $products = $lists['products'];
            foreach($products as $product){
                if($product['code'] == $code){
                    return $product;
                }
            }
        }
        return false;
    }
    public function purchase($number, $product, $price)
    {
        $url = "http://epayment.com.ng/epayment/api/3pcable_vend";
        $refNo = $this->genRefNo();
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            'service'=>$this->service,
            'smartcard_number'=>$number,
            'customer_reference'=>$refNo,
            'product_code'=>$product['code']
        ]);
        $data = json_decode($response, true);
        if(isset($data['status']) && $data['status'] == 201){
            $cable = ECableTv::where('code', $this->service)->first();
            $user = Auth::user();
            $user->trx_balance -= $price;
            $user->save();
            VtuTrx::create([
                'id'=>$refNo,
                'user_id'=>$user->id,
                'amount'=>$price,
                'type'=>'cabletv',
                'service'=>$cable->name,
                'description'=>$cable->name.' '
                .$product['name'].' ['.$number.']'.' subscription'
            ]);
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$price,
                'gnumber'=>$user->gnumber,
                'name'=>'trx_balance',
                'type'=>'debit',
                'description'=>$cable->name.' '
                .$product['name'].' ['.$number.']'.' subscription'
            ]);
            $this->creditCashBack($cable);
            $this->purchase_done = true;
            return true;
        }
        return false;
    }
    /**
     * Verify utility bills payment
     *
     * @return bollean
     */ 
    public function verifyPurchase($customer_reference)
    {

    }
    public function creditCashBack(ECableTv $cable)
    {
        $user = Auth::user();
        $user->pend_balance += $cable->comm_amt;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$cable->comm_amt,
            'gnumber'=>$user->gnumber,
            'name'=>'pend_balance',
            'type'=>'credit',
            'description'=>'Cash back from Cable TV bill payment'
        ]);
        $user->faccount->deca += $cable->comm_amt;
        $user->faccount->save();
        $this->creditUpline($cable, $user);
    }
    /**
     * Share referral commissions
     *
     * @return null
    */ 
    public function creditUpline(ECableTv $cable, User $user, $level = 1)
    {
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $formular = json_decode('[' . $cable->ref_amt . ']', true);
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
            'description'=>Helpers::ordinal($level)." Gen CableTV referral commision" 
        ]);
        $user->faccount->deca += $amt;
        $user->faccount->save();
        if($user->ref_gnum)
            $this->creditUpline($cable, $user, $level+1);
        else 
            return;
    }
}
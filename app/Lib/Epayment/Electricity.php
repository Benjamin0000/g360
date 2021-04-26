<?php
namespace App\Lib\Epayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\VtuTrx;
use App\Models\Register;
use App\Models\EDisco;
use App\Models\User;
class Electricity
{
    private $bearer;
    public $meter;
    public $amount;
    public $disco;
    public function __construct($meter, EDisco $disco, $amount)
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
       $this->meter = $meter;
       $this->amount = $amount;
       $this->disco = $disco;
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
            "service"=>$this->disco->code, 
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
            return ['error'=>'Please select a valid disco for your meter number'];
        }
    }
    public function purchase()
    {
        $user = Auth::user();
        $total = $this->amount + $this->disco->charge;
        if($this->amount <= 0 || $user->trx_balance < $total)
            return false;
        $url = "http://epayment.com.ng/epayment/api/3pelectricity_vend";
        $refCode = $this->genRefNo();
        $response = Http::withHeaders([
            'Authorization'=>"Bearer ".$this->bearer,
        ])->post($url, [
            "service"=>$this->disco->code,
            "customer_reference"=>$refCode,
            "meter"=>$this->meter,
            "amount"=>$this->amount
        ]);
        $data = json_decode($response, true);
        if(isset($data['status']) && $data['status'] == 201){
            VtuTrx::create([
                'id'=>$refCode,
                'user_id'=>$user->id,
                'amount'=>$this->amount,
                'type'=>'electricity',
                'service'=>$disco->name,
                'description'=>$data
                // 'description'=>$disco->name.' meter ['.$this->meter.'] Unit'.
            ]);
            $user->trx_balance -= $total;
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
            $this->creditCashBack($this->disco);
            return true;
        }
        return false;
    }
    public function creditCashBack(EDisco $disco)
    {
        $user = Auth::user();
        $user->pend_balance += $disco->comm_amt;
        $user->save();
        WalletHistory::create([
            'id'=>Helpers::genTableId(WalletHistory::class),
            'user_id'=>$user->id,
            'amount'=>$disco->comm_amt,
            'gnumber'=>$user->gnumber,
            'name'=>'pend_balance',
            'type'=>'credit',
            'description'=>'Cash back from Utility bill payment'
        ]);
        $user->faccount->deca += $disco->comm_amt;
        $user->faccount->save();
        $this->creditUpline($disco, $user);
    }
     /**
     * Verify utility bills payment
     *
     * @return bollean
     */ 
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
    /**
     * Share referral commissions
     *
     * @return null
    */ 
    private function creditUpline(EDisco $disco, User $user, $level = 1)
    {
        $cur = Helpers::LOCAL_CURR_SYMBOL;
        $formular = json_decode('[' . $disco->ref_amt . ']', true);
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
            'description'=>Helpers::ordinal($level)." Gen utility bill referral commision" 
        ]);
        $user->faccount->deca += $amt;
        $user->faccount->save();
        if($user->ref_gnum)
            $this->creditUpline($disco, $user, $level+1);
        else 
            return;
    }
}
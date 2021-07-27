<?php
namespace App\Lib\Epayment;
use App\Models\FAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\WalletHistory;
use App\Models\ElectHistory;
use App\Models\Register;
use App\Models\EDisco;
use App\Models\User;
class Electricity
{
    private $bearer;
    public $meter;
    public $amount;
    public $disco;
    public function __construct($meter, $disco, $amount)
    {
       $reg = Register::where('name', 'epay_bearer')->first();
       $this->bearer = $reg->value;
       $this->meter = $meter;
       $this->disco = $disco;
       $this->amount = $amount;
    }
    private function genRefNo()
    {
        $code = bin2hex(openssl_random_pseudo_bytes(7));
        $check = ElectHistory::find($code);
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
            $data2 = $this->validateMeter();
            ElectHistory::create([
                'id'=>$refCode,
                'user_id'=>$user->id,
                'name'=>isset($data2['name']) ? $data2['name'] : '',
                'address'=>isset($data2['address']) ? $data2['address'] : '',
                'product_id'=>$data['product_id'],
                'amount'=>$this->amount,
                'fee'=>$this->disco->charge,
                'pin_code'=>$data['pin_code'],
                'operator_name'=>$data['operator_name'],
                'customer_reference'=>$data['customer_reference'],
                'reference'=>$data['reference'],
                'meter_no'=>$data['target']
            ]);
            $user->trx_balance -= $total;
            $user->save();
            WalletHistory::create([
                'id'=>Helpers::genTableId(WalletHistory::class),
                'user_id'=>$user->id,
                'amount'=>$total,
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
            'description'=>'Electricity bill Cashback'
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
        return $data = json_decode($response, true);
    }
    /**
     * Share referral commissions
    */
    private function creditUpline(EDisco $disco, User $user, $level = 1)
    {
        $formula = json_decode('[' . $disco->ref_amt . ']', true);
        $levels = count($formula);
        if($level > $levels) return;
        if($user->placed_by)
            $user = User::where([ ['gnumber', $user->placed_by], ['status', 1] ])->first();
        else
            $user = User::where([ ['gnumber', $user->ref_gnum], ['status', 1] ])->first();
        if($user) {
            $amt = (float)$formula[$level - 1];
            $user->pend_balance += $amt;
            $user->save();
            WalletHistory::create([
                'id' => Helpers::genTableId(WalletHistory::class),
                'amount' => $amt,
                'user_id' => $user->id,
                'gnumber' => $user->gnumber,
                'name' => 'pend_balance',
                'type' => 'credit',
                'description' => Helpers::ordinal($level) . " Level Electricity bill commission"
            ]);
            if(!$user->faccount){
                #Create Finance Account if uplink don't have
               $faccount = FAccount::create([
                    'id'=>Helpers::genTableId(FAccount::class),
                    'user_id'=>$user->id
               ]);
            }else{
                $faccount = $user->faccount;
            }
            $faccount->deca += $amt;
            $faccount->save();
            if ($user->ref_gnum)
                $this->creditUpline($disco, $user, $level + 1);
        }
        return;
    }
}

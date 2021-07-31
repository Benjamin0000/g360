<?php
namespace App\Lib;
use Exception;
use Illuminate\Support\Facades\Http;
class Monnify
{
    private $basicToken;
    private $bearerToken;
    private $testEndpoint = "https://sandbox.monnify.com/api/v1/";
    private $prodEndpoint = "";
    private $endPoint;
    public function __construct($useBearer = true)
    {
        $key = env('MONNIFY_KEY');
        $secret = env('MONNIFY_SECRET');
        if(!$key || !$secret){
            throw new Exception("Monnify API key and Client Secrete must be set in the .env file");
        }
        $this->basicToken = base64_encode($key.':'.$secret);
        if(env('APP_DEBUG')) {
            $authEndpoint = $this->testEndpoint . 'auth/login/';
            $this->endPoint = $this->testEndpoint;
        }else {
            $authEndpoint = $this->prodEndpoint . 'auth/login/';
            $this->endPoint = $this->prodEndpoint;
        }
        if($useBearer) {
            $res = Http::withBasicAuth($key, $secret)->post($authEndpoint);
            $data = json_decode($res, true);
            if (isset($data['responseBody']) && isset($data['responseBody']['accessToken']))
                $this->bearerToken = $data['responseBody']['accessToken'];
            else
                throw new Exception("Could not retrieve access token");
        }
    }
    public function createAccount($ref, $bvn, $name, $email)
    {
        if(!$ctCode = env('MONNIFY_CT_CODE'))
            throw new Exception("Set a Monnify contract code in the .env file");
        $url = $this->endPoint.'bank-transfer/reserved-accounts';
        $res = Http::withToken($this->bearerToken)->post($url, [
            'accountName'=>$name.' G360',
            'accountReference'=>$ref,
            'currencyCode'=>'NGN',
            'contractCode'=>$ctCode,
            'customerName'=>$name,
            'customerEmail'=>$email,
            'customerBVN'=>$bvn
        ]);
        $data = json_decode($res, true);
        return $data;
    }
}

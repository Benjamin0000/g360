<?php 
namespace App\Http;

use Illuminate\Http\Request;
use App\Models\EmailVerify;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\Email;
use App\Models\Error;
use App\Models\Package;
use App\Models\WalletHistory;
use App\Models\Epin;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use Exception;

  /**
  *  Helpers Class for Helpers methods
  */
  class Helpers 
  {

    public const LOCAL_CURR_SYMBOL = '₦';
    public const TRX_BALANCE = 't_balance';
    public const PKG_BALANCE = 'pkg_balance';
    public const PEND_BALANCE = 'p_balance';
    public const WITH_BALANCE = 'w_balance';
    public const HEALTH_TOKEN = 'h_token';
    public const POINT_VALUE = 'pv';

	/**
     * Store Email Addresses
	 * 
	 * @param string $email 
	 * @param string $type
     * @return void
    */ 
	public static function storeEmail( string $email, string $type ) : void
	{
		$theEmail = Email::where('address', $email)->first();
		if( !$theEmail ){
			Email::create([
				'address'=>$email,
				'type'=>$type
			]);
		}else{
			$data = explode(' ', $theEmail->type);
			if ( !in_array($type, $data) ){
				 array_push($data, $type);
				 $theEmail->type = implode(' ', $data);
				 $theEmail->save();
			}
		}
	}

     /**
      * Get page count for table
      * @param \Illuminate\Http\Request $request
      * @param int $total 
      * @return int
     */ 
     public static function tableNumber( int $total ) : int
     {
         if( request()->page && request()->page != 1 )
             return ( request()->page*$total ) - $total + 1;
         return 1;
     }
 
     /**
      * Generates randome name for files
      * 
      * @param string $filename
      * @return string
     */ 	
     public static function fileRand(string $filename) 
     {
         $ext = pathinfo($filename, PATHINFO_EXTENSION);
         $token = bin2hex(openssl_random_pseudo_bytes(4));
         $file = "images/".$token.'.'.$ext;
         if(file_exists($file))return fileRand();
         return $token.'.'.$ext;
     }
     
      /**
      * Generate Email Verification token
      *
      * @return String 
     */
      public static function getVToken() : string
      {
        $token = bin2hex(openssl_random_pseudo_bytes(40));
        $check = EmailVerify::where('token', $token)->first();
        $check2 = PasswordReset::where('token', $token)->first();
        if(!$check && !$check2)
            return $token;
        return self::getVToken();
      }

     /**
      * Get IP address of visitors
      *
      * @return String 
     */
     public static function visitorIp() : string
     {
         $ipaddress = '';
         if (isset($_SERVER['HTTP_CLIENT_IP']))
             $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
         else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
             $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
         else if(isset($_SERVER['HTTP_X_FORWARDED']))
             $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
         else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
             $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
         else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
             $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
         else if(isset($_SERVER['HTTP_FORWARDED']))
             $ipaddress = $_SERVER['HTTP_FORWARDED'];
         else if(isset($_SERVER['REMOTE_ADDR']))
             $ipaddress = $_SERVER['REMOTE_ADDR'];
         else
             $ipaddress = '';
         return $ipaddress;
     }

     /**
      * Validate input fields in registration form
      * @param  string $field (field name)
      * @return string | void
     */
     public static function formE($field)
     {
        $value = request($field);
        switch($field){

            case 'title': 
                $valid = ['mr', 'mrs', 'miss'];
                if($value == '' || !in_array($value, $valid))
                    return "invalid title";
            break;

            case 'username':
                if(strlen($value) <= 0)
                    return "username is too short";
                elseif(strlen($value) >= 100)
                    return "first name is too long";
                else{
                    if(User::where('username', $value)->exists())
                        return 'username already exists';
                }
            break;

            case 'fname':
                if(strlen($value) <= 0)
                    return "first name is too short";
                elseif(strlen($value) >= 100)
                    return "first name is too long";
            break;

            case 'lname': 
                if(strlen($value) <= 0)
                    return "last name is too short";
                elseif(strlen($value) >= 100)
                    return "last name is too long";
            break; 

            case 'email': 
                if(strlen($value) <= 0)
                    return "email is too short";
                elseif(strlen($value) >= 100)
                    return "email is too long";
                if(!filter_var($value, FILTER_VALIDATE_EMAIL))
                    return "invalid email address";
                if(User::where('email', $value)->exists())
                    return 'email already exists';
            break;

            case 'phone': 
                $phoneUtil = PhoneNumberUtil::getInstance();
                try {
                    $proto = $phoneUtil->parse($value, "NG");
                    if($phoneUtil->isValidNumber($proto) == false)
                        return "invalid phone number";
                } catch (NumberParseException $e) {
                    Error::create([
                        'name'=>'phone number validation with libphonenumber',
                        'message'=>$err->getMessage()
                    ]);
                }
            break;

            case 'sponsor': 
                if(!User::where('gnumber', $value)->exists())
                    return "invalid sponsor code";
            break; 

            case 'password': 
                if(strlen($value) <= 0)
                    return "password is too short";
                elseif(strlen($value) >= 100)
                    return "password is too long";
            break;

            case 'confirm_password': 
                if($value !== request('password'))
                    return 'password don\'t  match';
            break;

            default: 
                throw new Exception("invalid field");
        }
        return null;
    }

    /**
      * Get gnumber for the default user
      * @param  void 
      * @return int || null
     */
    public static function defaultGnum()
    {
        $defaultUser = User::where('def_user', 1)->first();
        if($defaultUser)
            return $defaultUser->gnumber;
        return null;
    }

    /**
      * Generate gnumber for new users
      * @param  void 
      * @return int 
    */
    public static function genGnumber()
    {
        $id = mt_rand(10000000,99999999);
        if(!User::where('gnumber', $id)->exists())
            return $id;
        return self::genGnumber();
    } 
    /**
      * Generate user id for new users
      * @param  void 
      * @return int 
    */
    public static function genTableId($class)
    {
        $id = bin2hex(openssl_random_pseudo_bytes(5));
        if(!$class::where('id', $id)->exists())
            return $id;
        return self::genTableId($class);
    }
    /**
      * Generate epin
      * @param  void 
      * @return int 
    */
    public static function genEpin()
    {
        $pin = mt_rand(1000000000,9999999999);
        $check = Epin::where('code', $pin)->first();
        if(!$check)
            return $pin;
        return self::genEpin();
    }
    /**
      * Generate epin
      * @param  void 
      * @return int 
    */
    public static function countUserEpin($user_id, $type, $pkg)
    {
        switch($type){
            case 'used': 
                return Epin::where([ ['user_id', $user_id], ['status', 1], ['pkg_id', $pkg] ])->count();
            case 'open': 
                return Epin::where([ ['user_id', $user_id], ['status', 0], ['pkg_id', $pkg] ])->count();
            default: 
                throw Exception('Invalid Epin search value');
        }
    }
    /**
      * Ajax request ouput format
      * for login and registratoin
      * @param  array || object || null $out  
      * @return object | string
    */
    public static function ajaxOut($out = null,$status = true) 
    {
        if($status)
            $m =  ['status'=> true, 'msg'=> 'Action completed'];
        else
            $m =  ['status'=> false, 'msg'=> 'Action failed'];
        if(is_array($out) || is_object($out)){
            foreach($out as $k => $v)
                $m[$k] = $v; 
        }
        elseif(is_string($out)){
            $m['msg'] = $out;
            $m['status'] = $status;
        }
        elseif(is_null($out)){
            $m['status'] = $status;
            $m['msg'] = null;
        }
        return json_encode($m);
    }
    /**
      * Share referal earning
      * @param  void 
      * @return void
    */
    public static function shareRefCommission($pkg_id, $amount, $gnumber)
    {
        $free_pkg = 1;
        $package = Package::find($pkg_id);
        if($package){
            $user = User::where([ ['gnumber', $gnumber], ['status', 1] ])->first();
            if($user && $user->pkg_id == $package->id && $package->id > $free_pkg)
                self::refTree($pkg_id, $amount, $user->ref_gnum);
        }
    }
    /**
      * Get the referal tree
      * @param  void 
      * @return void
    */
    public static function refTree($pkg_id, $amount, $ref_gnum, $level=1)
    {
        $last_level = 15;
        if($level > $last_level)return;
        $user = User::where([ ['gnumber', $ref_gnum], ['status', 1] ])->first();
        if($user){
            self::getRefLevelAndCredit($pkg_id, $amount, $ref_gnum, $level);
            return self::refTree($pkg_id, $amount, $user->ref_gnum, $level+1);
        }
    }

    private static function getRefLevelAndCredit($pkg_id, $amount, $gnumber, $level)
    {
        $package = Package::find($pkg_id);
        $user = User::where([ ['gnumber', $gnumber], ['status', 1] ])->first();
        if($package && $user){
            $ref_percent = explode(',', $package->ref_percent);
            $ref_h_token = explode(',', $package->ref_h_token);
            $ref_pv = explode(',', $package->ref_pv);
            // $ref_basic_pv = current($ref_pv);
            
            if($user->canEarnFromLevel($level)){
                if($level > count($ref_percent)){
                    $cash_profit = (floatval(end($ref_percent)) / 100) * $amount;
                    $h_token_profit = (int)end($ref_h_token);
                    // $pv_profit = ($ref_basic_pv*$package->id) - end($ref_pv);
                    $pv_profit = (int)end($ref_pv);
                }
                else{
                    $cash_profit = (floatval($ref_percent[$level-1]) / 100) * $amount;
                    $h_token_profit = (int)$ref_h_token[$level-1];
                    // $pv_profit = ($ref_basic_pv*$package->id) - $ref_pv[$level-1];
                    $pv_profit = (int)$ref_pv[$level-1];
                }
                $user->p_balance += $cash_profit;
                $user->h_token += $h_token_profit;
                $user->pv += $pv_profit;
                WalletHistory::create([
                    'id'=>self::genTableId(WalletHistory::class),
                    'amount'=>$cash_profit,
                    'user_id'=>$user->id,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::PEND_BALANCE,
                    'type'=>'credit',
                    'description'=>self::LOCAL_CURR_SYMBOL.$cash_profit.' received from '.ucfirst($package->name).
                    ' package '.'level ' .$level.' referal commission' 
                ]);
                WalletHistory::create([
                    'id'=>self::genTableId(WalletHistory::class),
                    'amount'=>$h_token_profit,
                    'user_id'=>$user->id,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::HEALTH_TOKEN,
                    'type'=>'credit',
                    'description'=>$h_token_profit.' Health token received from '.ucfirst($package->name).
                    ' package '.'level '.$level.' referal commission' 
                ]);
                WalletHistory::create([
                    'id'=>self::genTableId(WalletHistory::class),
                    'amount'=>$pv_profit,
                    'user_id'=>$user->id,
                    'gnumber'=>$user->gnumber,
                    'name'=>self::POINT_VALUE,
                    'type'=>'credit',
                    'description'=>$pv_profit.' point value received from '.ucfirst($package->name).
                    ' package '.'level '.$level. ' referal commission' 
                ]);
                $user->save();
            }
        }
    }
} 
 ?>
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\EmailVerify as Token;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\Error;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) 
    {
        if($request->ajax()){
            $okay = true;
            $fvalues = [
                'title','username',
                'fname','lname', 'phone',
                'sponsor','email','password'
            ];
            foreach($fvalues as $value){
                if($msg = Helpers::formE($value)){
                    $out['rv'][] = ['ele'=>$value,'msg'=>$msg, 'status'=>false];
                    $okay = false;
                }else{
                    $out['rv'][] = ['ele'=>$value,'msg'=> null,'status'=>true];
                }
            }
            if($okay){
                $data = $request->all();
                $data['id'] = Helpers::genUserId();
                $data['gnumber'] = Helpers::genGnumber();
                $data['password'] = Hash::make($data['password']);
                $data['ref'] = $data['sponsor'];
                $user = User::create($data);
           
                $token = Token::create([
                    'user_id'=>$data['id'],
                    'email'=>$user['email'],
                    'token'=>Helpers::getVToken()
                ]);

                $mailSent = false;
                try{
                    Helpers::storeEmail($token['email'], 'registered');
                    Mail::to($user)->send( new VerifyEmail($token) );
                    $mailSent = true; 
                }catch(Exception $err){
                    Error::create([
                        'name'=> 'email verification',
                        'message'=>$err->getMessage()
                    ]);
                }
                if(!$mailSent){
                    $user->wallet->delete();
                    $user->delete();
                    $token->delete();
                    die();
                }
            }
            return Helpers::ajaxOut($out, $okay);
        }
    }
    /**
     * Resend Verification
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function resendVerification()
    {
        
    }
    /**
     * Registation confirmation
     *
     * @param string $token
     * @param string $email
     */
    public function verifyEmail($token, $email) 
    {
        if( $token && $email ){
           $check  = Token::where([ 
               ['token', $token], 
               ['email', $email], 
               ['status', 0] 
            ])->first();
           if( $check ){
               if( $check->created_at->diffInHours() < 24 ){
                    if( $user = User::find($check->user_id) ){
                        $check->status = 1;
                        $check->save();
                        $user->email = $email;
                        if( $user->email_verified_at == '' ){
                            $user->email_verified_at = Carbon::now();
                            $r_to = 'pkg';
                            //create wallet;
                        }else 
                            $r_to = 'dash';
                        $user->save();

                        if($r_to == 'pkg'){
                            Auth::login($user);
                            return redirect( route('package.index') )->with('choose_pkg', 'select pkg');
                        }
                        else
                            return redirect( route('dashboard.index') )->with('email_verified', 'vefified');
                    }
               }
               $check->delete();
           }
        }
        return redirect( route('login') )->with('expired_link', 'expired');
    }
}

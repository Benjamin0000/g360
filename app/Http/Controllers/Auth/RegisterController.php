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
     * Creates a new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('verifyEmail');
    }
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
            $noError = true;
            $fvalues = [
                'title','username',
                'fname','lname', 'phone',
                'sponsor','pu','email','password',
                'confirm_password'
            ];
            foreach($fvalues as $value){
                if($msg = Helpers::formE($value)){
                    $out['rv'][] = ['ele'=>$value,'msg'=>$msg, 'status'=>false];
                    $noError = false;
                }else{
                    $out['rv'][] = ['ele'=>$value,'msg'=> null,'status'=>true];
                }
            }
            if($noError){
                $data = $request->all();
                $data['id'] = Helpers::genTableId(User::class);
                $data['gnumber'] = Helpers::genGnumber();
                $data['password'] = Hash::make($data['password']);
                if(isset($data['pu'])){
                    $data['ref_gnum'] = $data['pu'];
                    $data['placed_by'] = $data['sponsor'];
                }else{
                    $data['ref_gnum'] = $data['sponsor'];
                }
                $user = User::create($data);
                // $token = Token::create([
                //     'id'=>Helpers::genTableId(Token::class),
                //     'user_id'=>$user['id'],
                //     'email'=>$user['email'],
                //     'token'=>Helpers::getVToken()
                // ]);

                // $mailSent = false;
                // try{
                //     Helpers::storeEmail($token['email'], 'registered');
                //     Mail::to($user)->send( new VerifyEmail($token) );
                //     $mailSent = true; 
                // }catch(Exception $err){
                //     Error::create([
                //         'name'=> 'email verification',
                //         'message'=>$err->getMessage()
                //     ]);
                // }
                // if(!$mailSent){
                //     $user->delete();
                //     $token->delete();
                //     die(Helpers::ajaxOut('', false));
                // }
            }
            return Helpers::ajaxOut($out, $noError);
        }
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
                        }else 
                            $r_to = 'dash';
                        $user->save();

                        if($r_to == 'pkg'){
                            Auth::login($user);
                            return redirect( route('user.package.index') )->with('choose_pkg', 'select pkg');
                        }
                        else
                            return redirect( route('login') )->with('email_verified', 'vefified');
                    }
               }
               $check->delete();
           }
        }
        return redirect( route('login') )->with('expired_link', 'expired');
    }

    public function verifyGnumber(Request $request, $no=0)
    {
        if($request->ajax()){
            $user = User::where('gnumber', $no)->first();
            if($user){
                if($user->def_user){
                    return ['data'=>'Default'];
                }
                return ['data'=>$user->fname.' '.$user->lname];
            }
            return ['error'=>'User not found'];
        }
    }
}

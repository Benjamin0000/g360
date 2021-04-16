<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ChangePassword;
use App\Http\Helpers;
use App\Models\User;
use App\Models\Error;
use App\Models\PasswordReset as Token;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Creates a new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if(!$request->username || !$request->password)return;
        if( $check = User::where('username', $request->username)->first() ){
            if( password_verify($request->password, $check->password) ){
                $d = [
                    'd'=>  ['r'=>route('user.dashboard.index')]
                ];
                $rem = $request->rem ? true : false;
                Auth::login($check, $rem);
                $request->session()->flash('login_success', 'not empty');
                return Helpers::ajaxOut($d, true);
            }else
                die(Helpers::ajaxOut("username or password don't match", false));
        }
        die(Helpers::ajaxOut("Account don't exist", false));
    }

    public function sendResetLink(Request $request)
    {
        if($request->email){
            if($user = User::where('email', $request->email)->first()){
                $check = Token::where([
                    ['email', $request->email],
                    ['status', 0]
                ])->first();
                $allow = true;
                if( $check ){
                    if( $check->created_at->diffInHours() >= 12 )
                        $check->delete();
                    else
                        $allow = false;
                }
                if( $allow ){
                    $token = Token::create([
                        'id'=>Helpers::genTableId(Token::class),
                        'user_id'=>$user->id,
                        'email'=>$user->email,
                        'token'=>Helpers::getVToken()
                    ]);
                    try{
                        Mail::to($user)->send(new ChangePassword($token));
                    }catch(Exception $err){
                        Error::create([
                            'name'=> 'sending password link',
                            'message'=>$err->getMessage()
                        ]);
                        $token->delete();
                    }
                }
                die(Helpers::ajaxOut("email sent", true));
            }else{
                die(Helpers::ajaxOut("We did not find that email in our system", false));
            }
        }else{
            die(Helpers::ajaxOut("Enter a valid email address", false));
        }
    }

    public function showUpdatePassForm($token, $email)
    {
        $token = Token::where([
            ['email', $email],
            ['token', $token],
            ['status', 0]
        ])->first();
        if( $token ){
            if( $token->created_at->diffInHours() >= 12 ){
                #skip to bottom of parent IF
            }else
                return view('auth.update_pass', compact('token'));
            $token->delete();
        }
        return redirect( route('login') )->with('expired_link', 'expired');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password'=> ['required', 'min:6', 'confirmed']
        ]);

        if( $request->token && $request->email ){
            $check = Token::where([
                ['email', $request->email],
                ['token', $request->token],
                ['status', 0]
            ])->first();
            if( $check ){
                if( $check->created_at->diffInHours() >= 12 ){
                   #skip to bottom of parent IF
                }else{
                    if( $user = User::find($check->user_id) ){
                        $user->password = Hash::make($request->password);
                        $check->status = 1;
                        $user->save();
                        $check->save();
                        return redirect( route('login') )->with('password_changed', 'password changed');
                    }
                }
                $check->delete();
            }
        }
        return redirect( route('login') )->with('expired_link', 'expired');
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect( route('login') )->with('logout', 'not empty');
    }
}

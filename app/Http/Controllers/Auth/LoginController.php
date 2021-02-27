<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\User;
class LoginController extends Controller
{
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
        if(!$request->gnumber || !$request->password)return;
        if( $check = User::where('gnumber', $request->gnumber)->first() ){
            if( password_verify($request->password, $check->password) ){
                $d = [
                    'd'=>  ['r'=>route('dasbhoard.index')]
                ];
                $rem = $request->rem ? true : false;
                Auth::login($check, $rem);
                die(Helpers::ajaxOut($d, true));
            }else
                die(Helpers::ajaxOut("G-number or password don't match", false));
        }
        die(Helpers::ajaxOut("Account don't exist",false));
    }
   

    public function showEmailForm()
    {
        //
    }

    public function sendResetLink(Request $request)
    {
        //
    }

    public function showUpdatePassForm($token, $gnumber)
    {

    }

    public function updatePassword(Request $request)
    {
        
    }


    public function forgotGnumber()
    {
        //
    }

    public function getGnumber(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

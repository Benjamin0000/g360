<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers;
use App\Models\Admin as Super;
class LoginController extends Controller
{
  /**
   * Creates a new Controller instance
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('admin.guest')->except('logout');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
     if(!Super::first()){
         $admin = new Super();
         $admin->id =  Helpers::genTableId(Super::class);
         $admin->username = 'superuser';
         $admin->email = 'superuser@test.com';
         $admin->name = "Chike Cypriel";
         $admin->address = "will be updated";
         $admin->role = "sa";
         $admin->password = Hash::make('superuser');
         $admin->save();
     }
     return view('admin.login');
  }
  /**
   * Logs admin to app
   * @param  $request
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request)
  {
      $this->validate($request, [
          'username'=>['required', 'max:100'],
          'password'=>['required']
      ]);
      $check = Super::where('username', $request->username)->first();
      if($check){
         if(password_verify($request->password, $check->password)){
             Auth::guard('admin')->login($check);
             return redirect()->route('admin.dashboard.index');
         }else {return back()->with('error', 'invalid password');}
      }else{return back()->with('error', 'invalid username');}
  }
  /**
   * Log admin out
   *
   * @return \Illuminate\Http\Response
   */
  public function logout()
  {
      $admin = Auth::guard('admin');
      $admin->user()->save();
      Auth::guard('admin')->logout();
      Session::flash('admin_logout', 'successfull');
      return redirect('/');
  }
}

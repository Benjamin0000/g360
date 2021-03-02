<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
class PackageController extends Controller
{

    /**
    * Creates a new controller instance
    *
    * @return void
    */
   public function __construct()
   {
       $this->middleware('auth');
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.package.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPremiumPackages()
    {
        $packages = Package::where('name', '<>', 'free')->get();
        return view('user.package.premium', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selectFreePackage(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selectPremiumPackage(Request $request)
    {
        if(!$request->ajax())return;
        if(!$request->pay_method || 
           !$request->h || 
           !$request->p || 
           strlen($request->p) != 3 ||
           !in_array($request->h, ['yes', 'no'])
        )return['msg'=>'<i class=\'fa fa-info-circle\'></i> Can\'t process request at the moment'];
        // process payment
        $id = substr($request->p, 2, 1);
        $package = Package::find($id);
        if($id == 1 || !$package) 
        return ['msg'=>'<i class=\'fa fa-info-circle\'></i> Invalid package'];
        $done = $package->activate($request->h, $request->pay_method);
        if($done){
            return ['status'=>1, 'msg'=>'package successful'];
        }else{
            return ['msg'=>1, 'msg'=>'package could not be activated'];
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
class DownlineController extends Controller
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
    public function direct()
    {
        // $user = Auth::user();
        // $dReferals = User::where();
        // return view('user.downline.direct');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indirect()
    {
        // $user = Auth::user();
        // return view('user.downline.indirect');
    }

}

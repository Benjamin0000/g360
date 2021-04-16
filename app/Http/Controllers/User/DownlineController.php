<?php

namespace App\Http\Controllers\User;

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
        $user = Auth::user();
        $referals = User::where('ref_gnum', $user->gnumber)
        ->orWhere('placed_by', $user->gnumber)
        ->orderBy('cpv', 'DESC')
        ->latest()->paginate(10);
        return view('user.downline.direct', compact('referals'));
    }
    /**
     * Recursively get all indirect referrals
     *
     * @return \Illuminate\Http\Response
     */   
    private static function getIndirectRef($gnumber, &$referals, $level=1)
    {
        if($level > 15) return;
        $user = User::where('ref_gnum', $gnumber)->first();
        if($user){
            array_push($referals, $user);
            self::getRef($user->gnumber, $referals, $level+1);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indirect()
    {
       
    }

}

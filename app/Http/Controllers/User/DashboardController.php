<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use App\Models\User;

class DashboardController extends Controller
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
        $user = Auth::user();
        $histories = WalletHistory::where([ 
            ['user_id', $user->id],
            ['name', '<>', 'h_token'],
            ['name', '<>', 'pv']
        ])
        ->latest()->take(15)->get();
        $referals = [];
        $d_referals = User::where('ref_gnum', $user->gnumber)->latest()->get();
        if($d_referals->count()){
            foreach($d_referals as $d_referal){
                array_push($referals, $d_referal);
                self::getRef($d_referal->gnumber, $referals);
            }
        }
        return view('user.dashboard.index',  compact('histories', 'referals'));
    }
    private static function getRef($gnumber, &$referals, $level=1)
    {
        if($level > 15 || count($referals) >= 15) return;
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

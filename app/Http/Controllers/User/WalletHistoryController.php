<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WalletHistory;
use App\Http\Helpers;
class WalletHistoryController extends Controller
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
     * Withdrawal wallet
     *
     * @return \Illuminate\Http\Response
     */
    public function w_wallet()
    {
        $user = Auth::user();
        $histories = WalletHistory::where([ 
            ['user_id', $user->id],
            ['name', Helpers::WITH_BALANCE],
        ]);
        $total = $histories->count();
        $histories = $histories->latest()->paginate(10);
        return view('user.history.w_wallet', compact('histories', 'total'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function t_wallet()
    {
        $user = Auth::user();
        $histories = WalletHistory::where([ 
            ['user_id', $user->id],
            ['name', Helpers::TRX_BALANCE],
        ]);
        $total = $histories->count();
        $histories = $histories->latest()->paginate(10);
        return view('user.history.t_wallet', compact('histories', 'total'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function p_wallet()
    {
        $user = Auth::user();
        $histories = WalletHistory::where([ 
            ['user_id', $user->id],
            ['name', Helpers::PEND_BALANCE],
        ]);
        $total = $histories->count();
        $histories = $histories->latest()->paginate(10);
        return view('user.history.p_wallet', compact('histories', 'total'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function g_wallet()
    {
        $user = Auth::user();
        $histories = WalletHistory::where([ 
            ['user_id', $user->id],
            ['name', Helpers::LOAN_PKG_BALANCE],
        ]);
        $total = $histories->count();
        $histories = $histories->latest()->paginate(10);
        return view('user.history.g_wallet', compact('histories', 'total'));
    }
}

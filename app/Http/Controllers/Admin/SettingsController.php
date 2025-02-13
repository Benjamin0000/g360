<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Helpers;
use App\Models\Register;
use App\Models\Admin;
class SettingsController extends Controller
{
     /**
     * Creates a new Controller instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ppp(Request $request)
    {
        $this->validate($request, [
            'minutes'=>['required', 'numeric'],
            'grace_minutes'=>['required', 'numeric'],
            'total_referrals'=>['required', 'numeric'],
            'pv'=>['required', 'numeric'],
            'reward_amount'=>['required', 'numeric'],
            'payment'=>['required', 'numeric'],
            'grace_trail'=>['required', 'numeric'],
            'reactivation_fee'=>['required', 'numeric']
        ]);
        Helpers::saveRegData('ppp_minutes', $request->minutes);
        Helpers::saveRegData('ppp_grace_minutes', $request->grace_minutes);
        Helpers::saveRegData('ppp_total_referrals', $request->total_referrals);
        Helpers::saveRegData('ppp_pv', $request->pv);
        Helpers::saveRegData('ppp_reward_amount', $request->reward_amount);
        Helpers::saveRegData('ppp_payment', $request->payment);
        Helpers::saveRegData('ppp_grace_trail', $request->grace_trail);
        Helpers::saveRegData('ppp_r_fee', $request->reactivation_fee);
        return back()->with('success', 'Personal performance point updated');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePsharing(Request $request)
    {
        Helpers::saveRegData('p_share_formular', $request->formular);
        Helpers::saveRegData('vat', $request->vat);
        Helpers::saveRegData('h_token_price', $request->h_token_price);
        Helpers::saveRegData('bronz_coin_price', $request->bronz_coin_price);
        Helpers::saveRegData('silver_coin_price', $request->silver_coin_price);
        Helpers::saveRegData('gold_coin_price', $request->gold_coin_price);
        Helpers::saveRegData('min_with', $request->min_with);
        return back()->with('success', 'Updated');
    }
     /**
     * update Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password'=>['required', 'confirmed'],
            'current_password'=>['required']
        ]);
        $admin = Auth::guard('admin')->user();
        if(!password_verify($request->current_password, $admin->password))
            return back()->with('error', 'Incorrect account password');
        $admin->password = Hash::make($request->password);
        $admin->save();
        return back()->with('success', 'password updated');
    }
}

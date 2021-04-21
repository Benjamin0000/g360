<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\Register;
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
        return back()->with('success', 'Updated');
    }

}

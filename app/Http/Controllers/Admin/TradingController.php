<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradePkg;
class TradingController extends Controller
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
        return view('admin.trading.index');
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function package()
    {
        $packages = TradePkg::paginate(20);
        $total = $packages->count();
        return view('admin.trading.package.index', compact('packages', 'total'));
    }
    /**
     * Create new trading package
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createPackage(Request $request)
    {
        $this->validate($request, [
            'amount'=>['required'],
            'name'=>['required'],
            'pv'=>['required'],
            'referral_pv'=>['required'],
            'expiry_days'=>['required'],
            'referral_commission'=>['required'],
            'interest'=>['required']
        ]);
        TradePkg::create([
            'name'=>$request->name,
            'amount'=>$request->amount,
            'pv'=>$request->pv,
            'ref_pv'=>$request->referral_pv,
            'ref_percent'=>$request->referral_commission,
            'exp_days'=>$request->expiry_days,
            'interest'=>$request->interest
        ]);
        return back()->with('success', 'Plan created');
    }
    /**
     * Update Pacakge
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updatePackage(Request $request, $id)
    {
        $this->validate($request, [
            'amount'=>['required'],
            'name'=>['required'],
            'pv'=>['required'],
            'referral_pv'=>['required'],
            'expiry_days'=>['required'],
            'referral_commission'=>['required'],
            'interest'=>['required']
        ]);

        $pkg = TradePkg::find($id);
        if($pkg){
            $pkg->update([
                'name'=>$request->name,
                'amount'=>$request->amount,
                'pv'=>$request->pv,
                'ref_pv'=>$request->referral_pv,
                'ref_percent'=>$request->referral_commission,
                'exp_days'=>$request->expiry_days,
                'interest'=>$request->interest
            ]);
            return back()->with('success', 'Plan updated');
        }
        return back()->with('error', 'Plan not found');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePackage($id)
    {
        $pkg = TradePkg::find($id);
        if($pkg)
            $pkg->delete();
        return back()->with('error', 'Plan not found');
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

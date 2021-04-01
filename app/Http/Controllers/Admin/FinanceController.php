<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airtime;
use App\Models\DataSub;
class FinanceController extends Controller
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
        return view('admin.finance.vtu.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $airtimes = Airtime::all();
        $datasubs = DataSub::all();
        return view('admin.finance.vtu.settings.index', compact('airtimes', 'datasubs'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAirtime(Request $request, $id)
    {
        if($network = Airtime::find($id)){
            $this->validate($request, [
                'min_buy'=>['required'],
                'max_buy'=>['required'],
                'commission'=>['required'],
                'referral_amount'=>['required']
            ]);
            $network->update([
                'min_buy'=>$request->min_buy,
                'max_buy'=>$request->max_buy,
                'comm'=>$request->commission,
                'ref_amt'=>$request->referral_amount
            ]);
            return back()->with('success', 'Network airtime updated');
        }else{
            return back()->with('error', 'network not found');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateData(Request $request, $id)
    {
        if($network = DataSub::find($id)){
            $this->validate($request, [
                'commission'=>['required'],
                'referral_amount'=>['required']
            ]);
            $network->update([
                'comm'=>$request->commission,
                'ref_amt'=>$request->referral_amount
            ]);
            return back()->with('success', 'Network data updated');
        }else{
            return back()->with('error', 'network not found');
        }
    }
}

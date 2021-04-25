<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airtime;
use App\Models\DataSub;
use App\Models\EDisco;
use App\Models\LoanSetting;
use App\Models\Loan;
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
     * Update Mobile data
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
    /**
     * Show electricity page
     *
     * @return \Illuminate\Http\Response
     */
    public function electricity()
    {
        return view('admin.finance.elect.index');
    }
    /**
     * Show electricity settings page
     *
     * @return \Illuminate\Http\Response
     */
    public function electSettings()
    {
        $discos = EDisco::all();
        return view('admin.finance.elect.settings', compact('discos'));
    }
     /**
     * Update electricity discos
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDisco(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>['required', 'max:100'],
            'charge'=>['required', 'numeric'],
            'comm_amt'=>['required', 'numeric'],
            'ref_amt'=>['required']
        ]);
        if($request->input('code')){
            return back();
        }
        if($disco = EDisco::find($id)){
            $disco->update($request->all());
            return back()->with('success', 'Disco updated');
        }   
        return back()->with('error', 'Disco not found');
    }
    /**
     * Show Loan page
     *
     * @return \Illuminate\Http\Response
     */
    public function loan()
    {
        $loans = Loan::latest()->paginate(10);
        return view('admin.finance.loan.index', compact('loans'));
    }
    /**
     * Show Loan settings page
     *
     * @return \Illuminate\Http\Response
     */
    public function loanSettings()
    {
        $loanPlans = LoanSetting::all();
        return view('admin.finance.loan.setting.index', compact('loanPlans'));
    }
    /**
     * Update LoanSettings
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function updateLoanSettings(Request $request, $id="")
    {
        $this->validate($request, [
            'name'=>['required'],
            'min'=>['required', 'numeric'],
            'max'=>['required', 'numeric'],
            'interest'=>['required', 'numeric'],
            'grace_interest'=>['required', 'numeric'],
            'expiry_months'=>['required', 'numeric'],
            'grace_months'=>['required', 'numeric']
        ]);
        $id = (int)$id;
        $data = $request->all();
        $data['f_interest'] = $data['grace_interest'];
        $data['exp_months'] = $data['expiry_months'];
        if($id){
            if($plan = LoanSetting::find($id)){
                $plan->update($data);
                return back()->with('success', 'Loan Plan updated');
            }
            return back()->with('error', 'Loan Plan not found');
        }else{
            LoanSetting::create($data);
            return back()->with('success', 'Loan Plan Created');
        }
    }
    /**
     * Delete Loan plan
     * @param $id 
     * @return \Illuminate\Http\RedirectResponse
    */
    public function deleteLoanPlan($id)
    {
        if($plan = LoanSetting::find($id)){
            $plan->delete();
            return back()->with('success', 'Loan Plan deleted');
        }
        return back()->with('error', 'Loan plan not found');
    }
}

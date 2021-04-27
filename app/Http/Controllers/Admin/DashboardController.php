<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ABalanceHistory;
use App\Http\Helpers;
class DashboardController extends Controller
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
        $total_users = User::count();
        $vat = (float)Helpers::getRegData('total_vat');
        $gsteam_fee = (float)Helpers::getRegData('total_gsteam_fee');
        $with_bal = User::sum('with_balance');
        $pend_bal = User::sum('pend_balance');
        $trx_bal = User::sum('trx_balance');
        $pkg_bal = User::sum('pkg_balance');
        $loan_elig_bal = User::sum('loan_elig_balance');
        $total_loan_bal = User::sum('total_loan_balance');

        return view('admin.dashboard.index',
            compact(
                'total_users', 'vat',
                'with_bal', 'pend_bal',
                'trx_bal', 'pkg_bal',
                'loan_elig_bal', 'total_loan_bal','gsteam_fee'
            )
        );
    }
    /**
     * Show vat history page
     *
     * @return \Illuminate\Http\Response
     */
    public function vat_page()
    {
        $vat = (float)Helpers::getRegData('total_vat');
        $histories = ABalanceHistory::where('name', 'vat')->latest()->paginate(10);
        return view('admin.dashboard.vat_page', 
        compact('vat', 'histories'));
    }
    /**
     * Show GsTeam fee history page
     *
     * @return \Illuminate\Http\Response
     */
    public function gsteam_fee_page()
    {
        $gsteam_fee = (float)Helpers::getRegData('total_gsteam_fee');
        $histories = ABalanceHistory::where('name', 'gsfee')->latest()->paginate(10);
        return view('admin.dashboard.gsteam_fee_page', 
        compact('gsteam_fee', 'histories'));
    }
    /**
     * Debit Vat 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function debitVat(Request $request)
    {
        $this->validate($request, [
            'amount'=>['required', 'numeric'],
            'purpose'=>['required', 'max:200']
        ]);
        $amount = $request->amount;
        if($amount < 0) return back()->with('error', 'Invalid amount');
        $gsteam_fee = (float)Helpers::getRegData('total_gsteam_fee');
        if($gsteam_fee > $amount){
            $gsteam_fee -= $amount;
            Helpers::saveRegData('total_gsteam_fee', $gsteam_fee);
            return back()->with('success', 'Deducted');
        }else{
            return back()->with('error', 'Insufficient fund');
        }
    }
    /**
     * Debit GSTEAM Fee
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function debitGsTeam(Request $request)
    {
        $this->validate($request, [
            'amount'=>['required', 'numeric'],
            'purpose'=>['required', 'max:200']
        ]);
        $amount = $request->amount;
        if($amount < 0) return back()->with('error', 'Invalid amount');
        $total_vat = (float)Helpers::getRegData('total_vat');
        if($total_vat > $amount){
            $total_vat -= $amount;
            Helpers::saveRegData('total_vat', $total_vat);
            return back()->with('success', 'Deducted');
        }else{
            return back()->with('error', 'Insufficient fund');
        }
    }
}

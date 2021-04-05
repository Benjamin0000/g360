<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airtime;
use App\Models\DataSub;
class EFinanceController extends Controller
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
        
        return view('user.e_finance.index');
    }
    /**
     * Show electrical pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function electricity()
    {
        return view('user.e_finance.pay_bills.electricity.index');
    }
    /**
     * Show airtime/data pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function airtimeData()
    {
        $airtimes = Airtime::all();
        $datasub = DataSub::all();
        return view('user.e_finance.pay_bills.airtime_data.index', compact('airtimes', 'datasub'));
    }
    /**
     * Show Cable tv pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function tvSub()
    {
        return view('user.e_finance.pay_bills.tvsub.index');
    }
    /**
     * Show waterSub pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function waterSub()
    {
        return view('user.e_finance.pay_bills.waterSub.index');
    }
}

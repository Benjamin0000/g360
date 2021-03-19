<?php
namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayBillsController extends Controller
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
        return view('user.pay_bills.index');
    }
    /**
     * Show electrical pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function electricity()
    {
        return view('user.pay_bills.electricity.index');
    }
    /**
     * Show airtime/data pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function airtimeData()
    {
        return view('user.pay_bills.airtime.index');
    }
    /**
     * Show Cable tv pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function tvSub()
    {
        return view('user.pay_bills.tvsub.index');
    }
    /**
     * Show waterSub pay bills page
     *
     * @return \Illuminate\Http\Response
     */
    public function waterSub()
    {
        return view('user.pay_bills.waterSub.index');
    }
}

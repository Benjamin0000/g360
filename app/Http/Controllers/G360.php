<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Helpers;

class G360 extends Controller
{
    public static $cur = Helpers::LOCAL_CURR_SYMBOL;
    public static $trx_balance = Helpers::TRX_BALANCE;
    public static $with_balance = Helpers::WITH_BALANCE;
    public static $pend_balance = Helpers::PEND_BALANCE;
    public static $pkg_balance = Helpers::PKG_BALANCE;
    public static $award_point = Helpers::AWARD_POINT;
    public static $h_token = Helpers::HEALTH_TOKEN;
    public static $pend_trx_balance = Helpers::PEND_TRX_BALANCE;
    public static $loan_elig_balance = Helpers::LOAN_ELIG_BALANCE;
    public static $total_loan_balance = Helpers::TOTAL_LOAN_BALANCE;
    public static $month_end = 27;
    public static $loan_interest = 10;
    public static $h_token_price = 15;
}

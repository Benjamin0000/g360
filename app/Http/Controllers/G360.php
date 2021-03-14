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
    public static $loan_pkg_balance = Helpers::LOAN_PKG_BALANCE;
    public static $award_point = Helpers::AWARD_POINT;
    public static $pend_trx_balance = Helpers::PEND_TRX_BALANCE;
    public static $loan_elig_balance = Helpers::LOAN_ELIG_BALANCE;
}

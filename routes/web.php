<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GmarketController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PackageController;
use App\Http\Controllers\User\EpinController;
use App\Http\Controllers\User\GfundController;
use App\Http\Controllers\User\WalletHistoryController;
use App\Http\Controllers\User\DownlineController;
use App\Http\Controllers\User\LoanController;
use App\Http\Controllers\User\RewardController;
use App\Http\Controllers\User\PayBillsController;
use App\Http\Controllers\User\GsClubController;
use App\Http\Controllers\User\ShopController as UShop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Lib\Interswitch\BillPayment;
use App\Http\Controllers\Admin\DashboardController as ADashboard;
use App\Http\Controllers\Admin\TradingController as ATrading;
use App\Http\Controllers\Admin\LoginController as ALogin;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', function(){
    // $url = 'https://sandbox.interswitchng.com/passport/oauth/token';
    $clientID = "IKIA452BC16732BA7D1D51881FBEC6A2E0B07529B10B";
    $secret = "Q1KG+0DqHE0k40z4unA1MDW8cfyjhNsUdwEADUbqwvQ=";
    // $data = "IKIA452BC16732BA7D1D51881FBEC6A2E0B07529B10B:Q1KG+0DqHE0k40z4unA1MDW8cfyjhNsUdwEADUbqwvQ=";
    $bill = new BillPayment($clientID, $secret);
    return $bill->get_billers();
    // $encoded = base64_encode($data);
    // $response = Http::withHeaders([
    //     'Authorization'=>"Basic ".$encoded,
    // ])->post($url, [
    //     "grant_type"=>"client_credentials",
    // ]);
    // return $response;
});

Route::get('/',  [FrontController::class, 'index'])->name('front.index');
Route::get('/about',  [FrontController::class, 'about'])->name('front.about');
Route::get('/how-it-works',  [FrontController::class, 'how_works'])->name('front.how_works');
Route::get('/services',  [FrontController::class, 'services'])->name('front.services');
Route::get('/terms-and-condition', [FrontController::class, 'terms'])->name('front.terms');
#store
Route::get('/general-market', [GmarketController::class, 'index'])->name('gm.index');
#login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

#pass reset
Route::post('/reset', [LoginController::class, 'sendResetLink'])->name('pass.sendResetLink');
Route::get('/password/reset/{token}/{email}', [LoginController::class, 'showUpdatePassForm'])->name('pass.showUpdateForm');
Route::post('/password/update', [LoginController::class, 'updatePassword'])->name('pass.updatePass');

#registration
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::get('/verify-email/{token}/{email}', [RegisterController::class, 'verifyEmail'])->name('register.verify');

#user
Route::group(['prefix'=>'portal'],  function(){
    Route::get('/', [DashboardController::class, 'index'])->name('user.dasbhoard.index');
    Route::post('/kAVRnEzhwNxKXuZ', [DashboardController::class, 'reactivateSuperAssoc'])->name('user.dashboard.rassoc');
    Route::post('/zGPLAHHAsPLgJzE', [DashboardController::class, 'reactivatePPP'])->name('user.dashboard.rappp');
    Route::get('/packages', [PackageController::class, 'index'])->name('user.package.index');
    Route::get('/packages/premium', [PackageController::class, 'showPremiumPackages'])->name('user.package.show_premium');
    Route::post('/packages/premium', [PackageController::class, 'selectPremiumPackage'])->name('user.package.select_premium');
    Route::post('/packages/free', [PackageController::class, 'selectFreePackage'])->name('user.package.select_free');
    #Gfund
    Route::get('/gfund', [GfundController::class, 'index'])->name('user.gfund.index');
    Route::post('/SaxRwepRJAHAIzG', [GfundController::class, 'withdrawalWalletTransfer'])->name('user.gfund.withdrawalTransfer');
    Route::post('/tMsUqzRZTCSUuZh', [GfundController::class, 'trxWalletTransfer'])->name('user.gfund.trxWalletTransfer');
    Route::post('/1bCwyWnqlC8qzbL', [GfundController::class, 'getMemeberDetail'])->name('user.gfund.getMemeberDetail');
    Route::post('/LSF5Z9ozY3cwLGA', [GfundController::class, 'transMembers'])->name('user.gfund.transMembers');
    Route::post('/kKCQFLskAdHXQxs', [GfundController::class, 'transBankAccount'])->name('user.gfund.transBankAccount');
    #Epin
    Route::get('/epin', [EpinController::class, 'index'])->name('user.epin.index');
    Route::get('/epin/buy', [EpinController::class, 'buy'])->name('user.epin.buy');
    Route::post('/epin/buy', [EpinController::class, 'issueBuy'])->name('user.epin.buy');
    Route::get('/epin/{pkg}', [EpinController::class, 'show'])->name('user.epin.show');
    #Transaction history
    Route::get('/history/W-wallet', [WalletHistoryController::class, 'w_wallet'])->name('user.history.w_wallet');
    Route::get('/history/T-wallet', [WalletHistoryController::class, 't_wallet'])->name('user.history.t_wallet');
    Route::get('/history/P-wallet', [WalletHistoryController::class, 'p_wallet'])->name('user.history.p_wallet');
    Route::get('/history/G-wallet', [WalletHistoryController::class, 'g_wallet'])->name('user.history.g_wallet');
    #downline
    Route::get('/downline/direct', [DownlineController::class, 'direct'])->name('user.downline.direct');
    Route::get('/downline/indirect', [DownlineController::class, 'indirect'])->name('user.downline.indirect');
    #loan
    Route::get('/loan', [LoanController::class, 'index'])->name('user.loan.index');
    Route::get('/loan/apply', [LoanController::class, 'apply'])->name('user.loan.apply');
    Route::post('/loan/request-loan', [LoanController::class, 'requestLoan'])->name('user.loan.requestLoan');
    Route::post('/loan/pay', [LoanController::class, 'pay'])->name('user.loan.pay');
    Route::post('/loan/approve/{id}', [LoanController::class, 'loanApprove'])->name('user.loan.approve');
    Route::post('/loan/extend/{id}', [LoanController::class, 'loanExtend'])->name('user.loan.loanExtend');
    #Reward
    Route::get('/reward', [RewardController::class, 'index'])->name('user.reward.index');
    Route::post('/reward/loan/{id}', [RewardController::class, 'selectLoan'])->name('user.reward.loan');
    Route::post('/reward/lmp/{id}', [RewardController::class, 'selectLmp'])->name('user.reward.lmp');
    #paybills
    Route::get('/pay-bills', [PayBillsController::class, 'index'])->name('user.pay_bills.index');
    Route::get('/pay-bills/electricity', [PayBillsController::class, 'electricity'])->name('user.pay_bills.elect.index');
    Route::get('/pay-bills/airtime-data', [PayBillsController::class, 'airtimeData'])->name('user.pay_bills.airtimeData.index');
    Route::get('/pay-bills/water', [PayBillsController::class, 'waterSub'])->name('user.pay_bills.waterSub.index');
    Route::get('/pay-bills/tv', [PayBillsController::class, 'tvSub'])->name('user.pay_bills.tvSub.index');
    #shop
    Route::get('/shop', [UShop::class, 'index'])->name('user.shop.index');
    Route::get('/shop/create', [UShop::class, 'create'])->name('user.shop.create');
    Route::post('/shop', [UShop::class, 'store'])->name('user.shop.save');
    Route::get('/shop/{id}', [UShop::class, 'edit'])->name('user.shop.edit');
    Route::put('/shop/{id}', [UShop::class, 'update'])->name('user.shop.update');
    Route::delete('/shop/{id}', [UShop::class, 'destroy'])->name('user.shop.destroy');
    Route::get('/BRrbPKqRyyJcMei/{name?}', [UShop::class, 'getCities'])->name('user.shop.getCities');
    #GTClub
    Route::get('/gsteam', [GsClubController::class, 'index'])->name('user.gsclub.index');
    Route::get('/myueynjsyh', [GsClubController::class, 'moreHistories'])->name('user.gsclub.morehis');
    Route::post('/NalQpdnl', [GsClubController::class, 'cashout'])->name('user.gsclub.cashout');
});

#Admin
Route::group(['prefix'=>'admin'],  function(){
   Route::get('/', [ADashboard::class, 'index'])->name('admin.dashboard.index');
   Route::get('/login', [ALogin::class, 'index'])->name('admin.login');
   Route::post('/login', [ALogin::class, 'login'])->name('admin.login');
   Route::post('/logout', [ALogin::class, 'logout'])->name('admin.logout');
   #Trading
   Route::get('/trading', [ATrading::class, 'index'])->name('admin.trading.index');
   Route::get('/trading/package', [ATrading::class, 'package'])->name('admin.trading.package');
   Route::post('/trading/package', [ATrading::class, 'createPackage'])->name('admin.trading.createPackage');
});

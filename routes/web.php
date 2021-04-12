<?php
use App\Lib\Epayment\Airtime;
use App\Lib\Epayment\Data;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GmarketController;
use App\Http\Controllers\EShopController;

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
use App\Http\Controllers\User\EFinanceController;
use App\Http\Controllers\User\GsClubController;
use App\Http\Controllers\User\ShopController as UShop;
use App\Http\Controllers\User\TradingController;
use App\Http\Controllers\User\PartnershipController;
use App\Http\Controllers\User\GmarketController as UGmarket;
use App\Http\Controllers\User\AgentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Lib\Interswitch\BillPayment;
use App\Http\Controllers\Admin\DashboardController as ADashboard;
use App\Http\Controllers\Admin\RankController as ARank;
use App\Http\Controllers\Admin\TradingController as ATrading;
use App\Http\Controllers\Admin\LoginController as ALogin;
use App\Http\Controllers\Admin\FinanceController as AFinance;
use App\Http\Controllers\Admin\GmarketController as AGMarket;
use App\Http\Controllers\Admin\PartnersController as APartner;
use App\Http\Controllers\Admin\AgentsController as AAgent;
use App\Http\Controllers\Admin\SettingsController as ASettings;
use App\Http\Controllers\Admin\PackageController as APackage;
use App\Http\Controllers\Admin\GsTeamController as AGsTeam;
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
    // $url = 'https://passport.interswitchng.com/passport/oauth/token';
    // $clientID = "IKIAC7E97F86359001BC72CDBD9BEE2AF4B3BEE7C2F9";
    // $secret = "3EC51A292F79ECAE110BA51348A88F34B620CE95";
    // $data = "$clientID:$secret";
    // $bill = new BillPayment($clientID, $secret);
    // return $bill->get_billers();
    // $encoded = base64_encode($data);
    // $response = Http::withHeaders([
    //     'Authorization'=>"Basic ".$encoded,
    //     'Content-Type'=>'application/x-www-form-urlencoded'
    // ])->post($url, [
    //     "grant_type"=>"client_credentials",
    // ]);

    // $url = 'http://epayment.com.ng/epayment/api/3pbundle_validate';
    // $response = Http::withHeaders([
    //     'Authorization'=>"Bearer An4jkYjFi0Q5HFvt5CyaZD6HP8GkM1TNJkoyXftrASROz3dBNVbqyLvPyaw9",
    // ])->post($url, [
    //     "phone"=>"2348115814769",
    // ]);

    // return json_decode($response, true);
    // $epay = new Airtime();
    // return $epay->validatePhone('2348115814769', 'Globacom', 100);
    // return $epay->purchase();
    // return $epay->product_code;
    $epay = new Data();
    return $epay->getDataPlan('07066571490');
});

// function SHA256($signaturecipher)

// {return hash("sha256",$signaturecipher);}

// function SHA1($signaturecipher)

// {return sha1($signaturecipher, true);}










Route::get('/',  [FrontController::class, 'index'])->name('front.index');
Route::get('/about',  [FrontController::class, 'about'])->name('front.about');
Route::get('/how-it-works',  [FrontController::class, 'how_works'])->name('front.how_works');
Route::get('/services',  [FrontController::class, 'services'])->name('front.services');
Route::get('/terms-and-condition', [FrontController::class, 'terms'])->name('front.terms');
#Gmarket
Route::get('/general-market', [GmarketController::class, 'index'])->name('gm.index');
#E-Shop
Route::get('/e-shop', [EShopController::class, 'index'])->name('eshop.index');
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
    #Efinance
    Route::get('/e-finance', [EFinanceController::class, 'index'])->name('user.efinance.index');
    #electricity
    Route::get('/e-finance/electricity', [EFinanceController::class, 'electricity'])->name('user.pay_bills.elect.index');
    Route::post('/e-finance/electricity', [EFinanceController::class, 'buyMeterUnit'])->name('user.pay_bills.elect.buy');
    #Airtime data
    Route::get('/e-finance/airtime-data', [EFinanceController::class, 'airtimeData'])->name('user.pay_bills.airtimeData.index');
    Route::post('/e-finance/airtime-data/A4RiBg5qS', [EFinanceController::class, 'buyAirtime'])->name('user.pay_bills.airtime');
    Route::post('/e-finance/airtime-data/ERgtj4Thy', [EFinanceController::class, 'getDataPlan'])->name('user.pay_bills.getdata_plan');
    Route::post('/e-finance/airtime-data/edbuFsBubYr', [EFinanceController::class, 'purchaseData'])->name('user.pay_bills.purchaseData');
    Route::get('/e-finance/water', [EFinanceController::class, 'waterSub'])->name('user.pay_bills.waterSub.index');
    Route::get('/e-finance/tv', [EFinanceController::class, 'tvSub'])->name('user.pay_bills.tvSub.index');
    #Gmarket
    Route::get('/g-market', [UGmarket::class, 'index'])->name('user.gmarket.index');
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
    #Trading
    Route::get('/trading', [TradingController::class, 'index'])->name('user.trading.index');
    Route::post('/trading/{id}', [TradingController::class, 'selectPkg'])->name('user.trading.selectPkg');
    Route::get('/trading/history', [TradingController::class, 'history'])->name('user.trading.history');
    #partner
    Route::get('/partner', [PartnershipController::class, 'index'])->name('user.partnership.index');
    Route::post('/partner', [PartnershipController::class, 'cashout'])->name('user.partnership.cashout');
    #agent
    Route::get('/agent', [AgentController::class, 'index'])->name('user.agent.index');
    Route::get('/agent/apply', [AgentController::class, 'apply'])->name('user.agent.apply');
    Route::post('/agent/create', [AgentController::class, 'create'])->name('user.agent.create');
    Route::delete('/agent/delete/{id}', [AgentController::class, 'deleteRequest'])->name('user.agent.deleteRequest');
});

#Admin
Route::group(['prefix'=>'admin'],  function(){
   Route::get('/', [ADashboard::class, 'index'])->name('admin.dashboard.index');
   Route::get('/login', [ALogin::class, 'index'])->name('admin.login');
   Route::post('/login', [ALogin::class, 'login'])->name('admin.login');
   Route::post('/logout', [ALogin::class, 'logout'])->name('admin.logout');
   #Users

   #Rank
   Route::get('/rank', [ARank::class, 'index'])->name('admin.rank.index');
   Route::put('/rank/{id}', [ARank::class, 'update'])->name('admin.rank.update');
   #Trading
   Route::get('/trading', [ATrading::class, 'index'])->name('admin.trading.index');
   Route::get('/trading/package', [ATrading::class, 'package'])->name('admin.trading.package');
   Route::post('/trading/package', [ATrading::class, 'createPackage'])->name('admin.trading.createPackage');
   Route::put('/trading/pacakage/{id}', [ATrading::class, 'updatePackage'])->name('admin.trading.updatePackage');
   Route::delete('/trading/pacakage/{id}', [ATrading::class, 'deletePackage'])->name('admin.trading.deletePackage');
   #VTU
   Route::get('/vtu', [AFinance::class, 'index'])->name('admin.finance.vtu');
   Route::get('/vtu/settings', [AFinance::class, 'settings'])->name('admin.finance.vtu.settings');
   Route::put('/vtu/settings/airtime/{id}', [AFinance::class, 'updateAirtime'])->name('admin.finance.vtu.updateAirtime');
   Route::put('/vtu/settings/data/{id}', [AFinance::class, 'updateData'])->name('admin.finance.vtu.updateData');
   #Gmarket
   Route::get('/gmarket/shop', [AGMarket::class, 'shop'])->name('admin.gmarket.shop');
   #Partner
   Route::get('/partner', [APartner::class, 'index'])->name('admin.partner.index');
   Route::post('/partner', [APartner::class, 'store'])->name('admin.partner.store');
   Route::put('/partner/{id}', [APartner::class, 'update'])->name('admin.partner.update');
   Route::delete('/partner/{id}', [APartner::class, 'destroy'])->name('admin.partner.delete');
   Route::get('/partner/contract/{id}', [APartner::class, 'contract'])->name('admin.pcontract.index');
   Route::put('/partner/create-contract/{id}', [APartner::class, 'createContract'])->name('admin.pcontract.create');
   Route::delete('/partner/contract/{id}', [APartner::class, 'destroyContract'])->name('admin.pcontract.delete');
   Route::get('/partner-cashout', [APartner::class, 'cashout'])->name('admin.partner.cashout');
   Route::patch('/partner-cashout/process/{id}', [APartner::class, 'processCashout'])->name('admin.partner.processCashout');
   #Agents
   Route::get('/agents', [AAgent::class, 'index'])->name('admin.agents.index');
   Route::post('/agents', [AAgent::class, 'create'])->name('admin.agents.create');
   Route::get('/agents/new', [AAgent::class, 'newAgents'])->name('admin.agents.new');
   Route::patch('/agents/makesupper/{id}', [AAgent::class, 'makeSuper'])->name('admin.agents.makeSuper');
   Route::patch('/agents/approve/{id}', [AAgent::class, 'approveRequest'])->name('admin.agents.approve');
   Route::delete('/agents/disapprove/{id}', [AAgent::class, 'disApproveRequest'])->name('admin.agents.disapprove');
   Route::get('/agents/settings', [AAgent::class, 'settings'])->name('admin.agents.settings');
   Route::post('/agents/settings/update', [AAgent::class, 'updateAgent'])->name('admin.agents.settings.update');
   Route::post('/superagents/settings/update', [AAgent::class, 'updateSuperAgent'])->name('admin.superagent.settings.update');
   Route::get('/settings', [ASettings::class, 'index'])->name('admin.settings.index');
   Route::post('/settings/ppp', [ASettings::class, 'ppp'])->name('admin.settings.ppp');
   #package
   Route::get('/admin/package', [APackage::class, 'index'])->name('admin.package.index');
   Route::put('/admin/package/{id}', [APackage::class, 'update'])->name('admin.package.update');
   #Gs-Team
   Route::get('/admin/gsteam', [AGsTeam::class, 'index'])->name('admin.gsteam.index');
   Route::get('/admin/gsteam-settings', [AGsTeam::class, 'settings'])->name('admin.gsteam.settings');
   Route::put('/admin/gsteam-settings/{id}', [AGsTeam::class, 'update'])->name('admin.gsteam.update');
});

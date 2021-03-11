<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PackageController;
use App\Http\Controllers\User\EpinController;
use App\Http\Controllers\User\GfundController;
use App\Http\Controllers\User\WalletHistoryController;
use App\Http\Controllers\User\DownlineController;
use Illuminate\Support\Facades\Auth;
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
    $user = Auth::user();
    return $user->upgrade;
});

Route::get('/',  [FrontController::class, 'index'])->name('front.index');
Route::get('/about',  [FrontController::class, 'about'])->name('front.about');
Route::get('/how-it-works',  [FrontController::class, 'how_works'])->name('front.how_works');
Route::get('/services',  [FrontController::class, 'services'])->name('front.services');
Route::get('/terms-and-condition', [FrontController::class, 'terms'])->name('front.terms');
#store
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
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
});  

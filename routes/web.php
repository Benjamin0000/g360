<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PackageController;
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
// Route::get('/test', function(){
//     return view('user.layout');
// });

Route::get('/',  [FrontController::class, 'index'])->name('front.index');
Route::get('/about',  [FrontController::class, 'about'])->name('front.about');
Route::get('/how-it-works',  [FrontController::class, 'how_works'])->name('front.how_works');
Route::get('/services',  [FrontController::class, 'services'])->name('front.services');

#store
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
#login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

#pass reset
Route::get('/reset', [LoginController::class, 'showEmailForm'])->name('pass.showEmailForm');
Route::post('/reset', [LoginController::class, 'sendResetLink'])->name('pass.sendResetLink');
Route::get('/password/reset/{token}/{email}', [LoginController::class, 'showUpdateForm'])->name('pass.showUpdateForm');
Route::post('/password/update', [LoginController::class, 'updatePassword'])->name('pass.updatePass');

#registration
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::get('/verify-email/{token}/{email}', [RegisterController::class, 'verifyEmail'])->name('register.verify');

#user
Route::group(['prefix'=>'portal'],  function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dasbhoard.index');
    Route::get('/packages', [PackageController::class, 'index'])->name('package.index');
});

@extends('admin.layout', ['title'=>'SETTINGS'])
@section('content')
@php
use App\Http\Helpers;
use Carbon\Carbon;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
              <div class="d-flex m-b-40 align-items-center no-block">
                  <h5 class="card-title">SETTINGS</h5>
              </div>
               <div class="row">
                   <div class="col-md-3">
                       <h3 class="text-center"><b>PPP</b></h3>
                       <form action="{{route('admin.settings.ppp')}}" method="post">
                         <div class="form-group">
                             <label for="">Minutes</label>
                             <input class="form-control" type="text" name="minutes" value="{{Helpers::getRegData('ppp_minutes')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Grace Minutes</label>
                            <input class="form-control" type="text" name="grace_minutes" value="{{Helpers::getRegData('ppp_grace_minutes')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Required Referrals</label>
                            <input class="form-control" type="text" name="total_referrals" value="{{Helpers::getRegData('ppp_total_referrals')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Circle PV</label>
                            <input class="form-control" type="text" name="pv" value="{{Helpers::getRegData('ppp_pv')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Instant Reward amount</label>
                            <input class="form-control" type="text" name="reward_amount" value="{{Helpers::getRegData('ppp_reward_amount')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Circle Payment</label>
                            <input class="form-control" type="text" name="payment" value="{{Helpers::getRegData('ppp_payment')}}">
                         </div>
                         <div class="form-group">
                           <label for="">Total Grace Trail</label>
                           <input class="form-control" type="text" name="grace_trail" value="{{Helpers::getRegData('ppp_grace_trail')}}">
                         </div>
                         <div class="form-group">
                           <label for="">Reactivation Fee</label>
                           <input class="form-control" type="text" name="reactivation_fee" value="{{Helpers::getRegData('ppp_r_fee')}}">
                         </div>
                         @csrf
                          <div class="form-group">
                             <button class="btn btn-info btn-sm">Change</button>
                          </div>
                       </form>
                   </div>
                   <div class="col-md-3">


                    <h3 class="text-center"><b>Others</b></h3>
                    <form action="{{route('admin.settings.psharing')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Pend-Wallet Sharing formular</label>
                            <div>Ap, with, PKG, Loan, TRX</div>
                            <input type="text" name="formular" value="{{Helpers::getRegData('p_share_formular')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">VAT %</label>
                            <input name="vat" class="form-control" value="{{Helpers::getRegData('vat')}}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="">H-Token Price</label>
                            <input name="h_token_price" class="form-control" value="{{Helpers::getRegData('h_token_price')}}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="">Bronz Coin Price</label>
                            <input name="bronz_coin_price" class="form-control" value="{{Helpers::getRegData('bronz_coin_price')}}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="">Silver Coin Price</label>
                            <input name="silver_coin_price" class="form-control" value="{{Helpers::getRegData('silver_coin_price')}}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="">Gold Coin Price</label>
                            <input name="gold_coin_price" class="form-control" value="{{Helpers::getRegData('gold_coin_price')}}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="">Min Withdrawal</label>
                            <input name="min_with" class="form-control" value="{{Helpers::getRegData('min_with')}}" type="text">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-info">UPDATE</button>
                        </div>
                    </form>
                   </div>
                   <div class="col-md-3">
                     <form action="{{route('admin.settings.passd')}}" method="POST">
                         <div class="form-group">
                             <label for="">Current Password</label>
                             <input type="text" class="form-control" required name="current_password">
                         </div>
                         <div class="form-group">
                             <label for="">New password</label>
                             <input type="text" class="form-control" required name="password">
                         </div>
                         <div class="form-group">
                             <label for="">Confirm Password</label>
                             <input type="text" class="form-control" required name="password_confirmation">
                         </div>
                         @csrf
                         <div class="form-group">
                             <button class="btn btn-primary">Change</button>
                         </div>
                     </form>
                   </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection

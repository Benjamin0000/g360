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
                             <input type="text" name="minutes" value="{{Helpers::getRegData('ppp_minutes')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Grace Minutes</label>
                            <input type="text" name="grace_minutes" value="{{Helpers::getRegData('ppp_grace_minutes')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Required Referrals</label>
                            <input type="text" name="total_referrals" value="{{Helpers::getRegData('ppp_total_referrals')}}">
                         </div>
                         <div class="form-group">
                            <label for="">PV</label>
                            <input type="text" name="pv" value="{{Helpers::getRegData('ppp_pv')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Reward amount</label>
                            <input type="text" name="reward_amount" value="{{Helpers::getRegData('ppp_reward_amount')}}">
                         </div>
                         <div class="form-group">
                            <label for="">Payment</label>
                            <input type="text" name="payment" value="{{Helpers::getRegData('ppp_payment')}}">
                         </div>
                         <div class="form-group">
                           <label for="">Grace Trail</label>
                           <input type="text" name="grace_trail" value="{{Helpers::getRegData('ppp_grace_trail')}}">
                         </div>
                         <div class="form-group">
                           <label for="">Reactivation Fee</label>
                           <input type="text" name="reactivation_fee" value="{{Helpers::getRegData('ppp_r_fee')}}">
                         </div>
                         @csrf
                          <div class="form-group">
                             <button class="btn btn-info btn-sm">Change</button>
                          </div>
                       </form>
                   </div>
                   <div class="col-md-3">

                   </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection

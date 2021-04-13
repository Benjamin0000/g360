@extends('user.layout', ['title'=>'E-Finance'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<style media="screen">
.bill-l{
  display:inline-block;
  background:#eee;
  text-decoration: none;
  color:#555 !important;
  font-size: 20px;
  padding:10px;
  min-width: 150px;
  margin:10px;
  text-align: center;
  transition: 0.3s ease;
}
.bill-l:hover{
  background:#ccc;
}
</style>
<div class="card" style="margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>E-Finance</h3>
  </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">Total Earned
                </h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{$cur}}{{number_format($user->faccount->deca + $user->faccount->vtu_deca, 2, '.', ',')}}
                    </h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">Deca</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{number_format($user->faccount->deca + $user->faccount->vtu_deca, 2, '.', ',')}}
                    </h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">Bronze Coin</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{number_format($user->faccount->deca + $user->faccount->vtu_deca, 2, '.', ',')}}
                    </h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="card" style="min-height:100px;">
  <div class="card-body text-center">
    <a href="{{route('user.pay_bills.airtimeData.index')}}"  class="bill-l"><i class="fas fa-university text-primary"></i>Banking</a>
    <a href="{{route('user.pay_bills.airtimeData.index')}}"  class="bill-l"><i class="fas fa-mobile-alt text-info"></i> Airtime & Data</a>
    <a href="{{route('user.pay_bills.elect.index')}}"  class="bill-l"><i class="fas fa-bolt text-danger"></i> Electricity</a>
    {{-- <a href="{{route('user.pay_bills.waterSub.index')}}"  class="bill-l"><i class="fas fa-shower"></i> Water</a> --}}
    <a href="{{route('user.pay_bills.tvSub.index')}}"  class="bill-l"><i class="fas fa-tv text-warning"></i> Cable Tv</a>
  </div>
</div>
<script type="text/javascript">
   onReady(function(){});
</script>
@endsection

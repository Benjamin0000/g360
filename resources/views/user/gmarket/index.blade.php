@extends('user.layout', ['title'=>'General Market'])
@section('content')
@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style media="screen">
.gcon{background:#fff;border-color:#e6e6e6;min-height:100px;padding:11px;border:1px solid #e6e6e6;border-radius:4px;margin-bottom:10px;text-align:center}.gcon:hover{border-color:#00f;box-shadow:0 6px 12px rgb(0 0 0 / 10%)}
</style>
<div class="card" style="margin:0px;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>General Market</h3>
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
                      {{$cur}}0
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
                      0
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
                <h4 class="card-title">Silver Coin</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      0
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
<div class="card" style="margin:0px;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>Categories</h3>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <a href="{{route('user.shop.index')}}">
      <div class="gcon">
        <img class="" width="72" src="/assets/shop.png" alt="Restaurants">
        <h3 class="">Shops</h3>
      </div>
      </a>
  </div>

  {{-- <div class="col-md-3">
    <a href="#">
    <div class="gcon">
      <img class="" width="72" src="/assets/res.png" alt="Restaurants">
      <h3 class="">Restaurants</h3>
    </div>
    </a>
  </div> --}}

  {{-- <div class="col-md-3">
    <a href="#">
    <div class="gcon">
      <img class="" width="72" src="/assets/serve.png" alt="Restaurants">
      <h3 class="">Services</h3>
    </div>
    </a>
  </div> --}}

  <div class="col-md-3">
    <a href="#">
    <div class="gcon">
      <img class="" width="72" src="/assets/deliv.webp" alt="Restaurants">
      <h3 class="">Logistics</h3>
    </div>
    </a>
  </div>

  {{-- <div class="col-md-3">
    <a href="#">
    <div class="gcon">
      <img class="" width="72" src="/assets/night.png" alt="Restaurants">
      <h3 class="">Nightlife</h3>
    </div>
    </a>
  </div> --}}

</div>
@endsection

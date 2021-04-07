@extends('user.layout', ['title'=>'Agent'])
@php
use App\Http\Helpers;
$user = Auth::user();
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
@if($user->agent->status == 0)
<div class="card" style="margin:0;">
  <div class="card-body">
    <div class=""><b>State</b> : {{Helpers::getStateById($user->agent->state_id)}}</div>
    <div><b>Region</b> : {{Helpers::getStateById($user->agent->city_id)}}</div>
    <h3 class="text-center">Awaiting Approval</h3>
    <form  action="{{route('user.agent.deleteRequest', $user->agent->id)}}" method="post">
      @csrf
      @method('delete')
      <div class="text-center">
        <button onclick="return confirm('are you sure about this')" class="btn btn-danger">Delete Request</button>
      </div>
    </form>
  </div>
</div>
@else
<div class="card" style="margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>AGENT</h3>
  </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">FBronze Coin
                </h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{number_format($user->agent->fbronz_coin(), 2, '.', '')}}
                    </h2>
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
                <h4 class="card-title">GSiver Coin</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                        {{number_format($user->agent->gsilver_coin(), 2, '.', '')}}
                    </h2>
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
                <h4 class="card-title">POSDiamond Coin</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                        {{number_format($user->agent->posD_coin(), 2, '.', '')}}
                    </h2>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4" style="margin-top:10px;">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">RGold Coin</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                        {{number_format($user->agent->rgold_coin(), 2, '.', '')}}
                    </h2>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4" style="margin-top:10px;">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">Total Balance</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                        {{$cur.number_format($user->agent->totalBalance(), 2, '.', ',')}}
                    </h2>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

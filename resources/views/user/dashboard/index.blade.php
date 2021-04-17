@extends('user.layout', ['title'=>'Dashboard'])
@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
$last_pkg = App\Models\Package::orderBy('id', 'DESC')->first();
@endphp
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title float-left">Current package: <b>{{$user->package->id == $last_pkg->id ? strtoupper($last_pkg->name): ucfirst($user->package->name)}}</b></h4>
        @if($user->package->id != $last_pkg->id)
            <a href="{{route('user.package.select_premium')}}" class="btn btn-danger btn-sm float-right">UPGRADE</a>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">P-Wallet</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->pend_balance+$user->pend_trx_balance, 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
@if($user->pkg_id != $last_pkg->id)
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">PKG-Wallet</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->pkg_balance, 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
@endif
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">W-Wallet</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->with_balance, 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">T-Wallet</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->trx_balance, 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
@if($user->loanBalance() < 0)
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">LOAN-Wallet</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0 text-danger">{{$cur.number_format($user->loanBalance(), 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Group PV</h4>
            <div class="text-right">
                <h2 class="font-light mb-0"><i class="mdi mdi-trophy-award  text-info"></i>{{$user->cpv}}</h2>
                <span class="text-muted">Rank:</span> <span>{{$user->rank ? ucwords($user->rank->name): 'Associate'}}</span>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Award Point</h4>
            <div class="text-right">
                <h2 class="font-light mb-0"><i class="mdi mdi-trophy-award  text-info"></i>{{$user->award_point}}</h2>
                <span class="text-muted"><br></span>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>
@include('user.dashboard.ppp')
@include('user.dashboard.sa')
</div>
{{-- Transaction history --}}
@include('user.dashboard.trx_history')
@endsection

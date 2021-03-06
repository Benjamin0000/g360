@extends('user.layout', ['title'=>'Dasbhoard'])
@php $cur = App\Http\Helpers::LOCAL_CURR_SYMBOL @endphp
@php $user = Auth::user() @endphp
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title float-left">Current package: <b>{{$user->package->name=='vip'?'VIP': ucfirst($user->package->name)}}</b></h4>
        @if($user->package->name != 'vip')
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
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->p_balance, 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
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
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">W-Wallet</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->w_balance, 2, '.', ',')}}</h2>
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
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->t_balance, 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Monthly Bonus</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-led-on text-danger"></i> </h2>
                    <span class="text-muted">Monthly point accumulation</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Circle Bonus</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-led-on text-danger"></i> </h2>
                    <span class="text-muted">Monthly point accumulation</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Laurel Bonus</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-led-on text-danger"></i> </h2>
                    <span class="text-muted">Monthly point accumulation</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Travel Bonus</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-led-on text-danger"></i> </h2>
                    <span class="text-muted">Monthly point accumulation</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Point Value</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-trophy-award  text-info"></i>{{$user->pv}}</h2>
                    <span class="text-muted">Rank: </span>
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
                <h4 class="card-title">Deca / Coin Wallet</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-leaf text-purple"></i>{{$user->deca}} / {{intval($user->deca/1000)}}</h2>
                    <span class="text-muted">Accrued units</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-purple" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row -->
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex no-block">
                    <h4 class="card-title">Recent Referals</h4>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table stylish-table">
                        <thead>
                            <tr>
                                <th colspan="2">Name</th>
                                <th>Join Date</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if(count($referals))
                            @foreach($referals as $referal)
                              <tr>
                                <td>{{$referal->fname.' '.$referal->lname}}</td>
                                <td>{{$referal->created_at->isoFormat('lll')}}</td>
                              </tr>
                            @endforeach
                          @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex no-block">
                    <h4 class="card-title">Recent Transactions</h4>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table no-wrap table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                {{-- <th>Amount</th> --}}
                                <th>Wallet</th>
                                <th>Desc.</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if($histories->count())
                            @php $count = 1;@endphp
                            @foreach($histories as $history)
                              <tr>
                                <td>{{$count++}}</td>
                                {{-- <td>--}}
                                  {{-- {{$cur.$history->amount}} --}}
                                  {{-- @if($history->type == 'debit')
                                      <span class="badge badge-danger">Debit</span>
                                  @elseif($history->type == 'w_transfer')
                                      <span class="badge badge-danger">Credit</span>
                                  @elseif($history->type == 'pkg_transfer' || $history->type == 'trx_transfer')
                                    <span class="badge badge-warning">Transfer</span>
                                  @else
                                      <span class="badge badge-success">Credit</span>
                                  @endif --}}
                                {{-- </td>  --}}
                                <td>
                                  @if($history->name == 't_balance')
                                    T-wallet
                                  @elseif($history->name == 'w_balance')
                                    W-Wallet
                                  @elseif($history->name = 'pkg_balance')
                                    PKG-Wallet
                                  @elseif($history->name == 'p_balance')
                                    P-Wallet
                                  @endif
                                </td>
                                <td>{{$history->description}}</td>
                                <td>{{$history->created_at->isoFormat('lll')}}</td>
                              </tr>
                            @endforeach
                          @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

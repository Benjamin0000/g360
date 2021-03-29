@extends('user.layout', ['title'=>'Dasbhoard'])
@php
use Carbon\Carbon;
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
$last_rank_id = 10;
$sup_as_rank = App\Models\Rank::find(1);
$sa_fee = 5000;
@endphp
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
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>{{$cur.number_format($user->pend_balance+$user->pend_trx_balance, 2, '.', ',')}}</h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
@if($user->pkg_balance != $last_rank_id)
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
    {{-- <div class="col-lg-3 col-md-6">
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
    </div> --}}
    {{-- <div class="col-lg-3 col-md-6">
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
    </div> --}}
    {{-- <div class="col-lg-3 col-md-6">
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
    </div> --}}
    {{-- <div class="col-lg-3 col-md-6">
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
    </div> --}}
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
  @if($user->ppp && $user->ppp->status != 3)
   @php $ppp = $user->ppp @endphp
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="card-title"><span>PPP</span>  <span class="float-right">50kClub</span></h4>
                  <div class="">
                      <h2 class="font-light">
                        <span class="">
                          <i class="mdi mdi-trophy-award text-success"></i>{{$user->totalValidRef()}}
                        </span>
                        <span class="float-right">
                           <i class="mdi mdi-trophy-award text-danger"></i>{{$ppp->point}}
                        </span>
                      </h2>
                      <span class="text-muted" style="min-height:17px;display:block;"></span>
                  </div>
                  @if($ppp->status == 0)
                    <div class="text-center" style="margin-top:-22px;"><b style="color:black;" id="ddp"></b></div>
                    @php
                      if($ppp->graced_at != ''){
                         $date = $ppp->graced_at;
                         $days = 30;
                      }else{
                        $date = $ppp->created_at;
                        $days = 90;
                      }
                    @endphp
                    <script type="text/javascript">
                        onReady(function(){
                          var nextYear = moment.tz("{{Carbon::parse($date)->addDays($days)}}", 'Africa/Lagos');
                          $('#ddp').countdown(nextYear.toDate(), function(event) {
                            $(this).html(event.strftime('%D Days %H hrs : %M:%S'));
                          });
                        })
                    </script>
                  @elseif($ppp->status == 2)
                  <form action="{{route('user.dashboard.rappp')}}" method="post" class="text-center" style="padding:2px;margin-top:-27px;">
                    @csrf
                    <button onclick="return confirm('You will pay a reactivation fee of {{$cur}}10,000')" class="btn btn-primary btn-sm">Reactivate</button>
                  </form>
                  @endif
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
  @endif

    @if($associate = $user->superAssoc)
      @if($associate->status != 3)
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Super Associate</h4>
                    <div class="text-center">
                    @php $status = $associate->status @endphp
                    @if($status == 1)
                      <form  action="{{route('user.dashboard.rassoc')}}" method="post" onsubmit="return confirm('{{$cur}}{{$sa_fee}} will be charged as reactivation fee.')">
                        @csrf
                        <input type="hidden" name="type" value="ac">
                        <button class="btn btn-primary btn-sm">Reactivate</button>
                      </form>
                    @elseif($status == 2)
                          <button data-toggle='modal' data-target='#assoc' class="btn btn-success btn-sm blink"><i class="mdi mdi-trophy-award"></i> Claim now</button>
                          <div class="modal" tabindex="-1" id="assoc">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Super Associate Reward</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                   <form action="{{route('user.dashboard.rassoc')}}" method="post">
                                     <div class="form-group">
                                       <label for="">Select a reward type</label>
                                       <select class="form-control" name="tp">
                                          <option value="">Choose</option>
                                          @php $lmp = $sup_as_rank->total_lmp/$sup_as_rank->lmp_months @endphp
                                          <option value="m">{{$cur}}{{number_format($lmp)}} monthly payment</option>
                                          <option value="l">{{$cur}}{{number_format($sup_as_rank->loan)}} loan</option>
                                       </select>
                                     </div>
                                     @csrf
                                     <input type="hidden" name="type" value="cl">
                                     <div class="form-group">
                                        <button class="btn btn-success">Continue</button>
                                     </div>
                                   </form>
                                </div>
                              </div>
                            </div>
                          </div>
                      @elseif($associate->last_grace != '')
                          <h2 style="font-size:18px;" class="font-light mb-0">
                            <b><span id="clock"></span></b>
                          </h2>
                          @if($associate->balance_leg)
                            <div class="text-danger blink"><b>Balance your legs</b></div>
                          @endif
                          <script type="text/javascript">
                              onReady(function(){
                                var nextYear = moment.tz("{{Carbon::parse($associate->last_grace)->addDays(30)}}", 'Africa/Lagos');
                                $('#clock').countdown(nextYear.toDate(), function(event) {
                                  $(this).html(event.strftime('%D Days %H hrs : %M:%S'));
                                });
                              })
                          </script>
                      @else
                        <h2 style="font-size:18px;" class="font-light mb-0">
                          <b><span id="clock"></span></b>
                        </h2>
                        @if($associate->balance_leg)
                          <div class="text-danger blink"><b>Balance your legs</b></div>
                        @endif
                        <script type="text/javascript">
                            onReady(function(){
                              var nextYear = moment.tz("{{$associate->created_at->addDays(60)}}", 'Africa/Lagos');
                              $('#clock').countdown(nextYear.toDate(), function(event) {
                                $(this).html(event.strftime('%D Days %H hrs : %M:%S'));
                              });
                            })
                        </script>
                      @endif
                        <br>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-purple" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
      @endif
    @endif
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
                                <th>Amount</th>
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
                                <td>
                                  {{-- @if($history->name != "cpv" && $history->name != 'h_token') --}}
                                    {{-- {{$cur}} --}}
                                  {{-- @endif --}}
                                  {{$cur.number_format($history->amount,2,'.',',')}}
                                  @if($history->type == 'debit')
                                      <span class="badge badge-danger">Debit</span>
                                  @elseif($history->type == 'credit')
                                      <span class="badge badge-success">Credit</span>
                                  @endif
                                </td>
                                <td>
                                  @if($history->name == 'trx_balance')
                                    TRX-wallet
                                  @elseif($history->name == 'with_balance')
                                    W-Wallet
                                  @elseif($history->name == 'loan_pkg_balance')
                                    LOAN-PKG-Wallet
                                  @elseif($history->name == 'pend_balance')
                                    PEND-Wallet
                                  @elseif($history->name == 'cpv')
                                    POINT VALUE
                                  @elseif ($history->name = 'h_token')
                                    HEALTH TOKEN
                                  @elseif($history->name == 'award_point')
                                    POINT AWARD
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

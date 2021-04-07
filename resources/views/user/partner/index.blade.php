@extends('user.layout', ['title'=>'Partnership'])
@php
use Carbon\Carbon;
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
  @include('user.partner.cashout_modal')
<div class="card" style="margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>Partnership Account</h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-4" style="margin-bottom:10px;">
      <div class="card" style="margin:0;">
          <div class="card-body">
              <h4 class="card-title">Total Invested
              </h4>
              <div class="text-right">
                  <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                    {{$cur}}{{number_format($partner->total_invested(), 2, '.', ',')}}
                  </h2>
                  <span class="text-muted"></span>
              </div>
              <div class="progress">
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
          </div>
      </div>
  </div>
  <div class="col-lg-4" style="margin-bottom:10px;">
      <div class="card" style="margin:0;">
          <div class="card-body">
              <h4 class="card-title">Currently Invested
              </h4>
              <div class="text-right">
                  <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                    {{$cur}}{{number_format($partner->total_currently_invested(), 2, '.', ',')}}
                  </h2>
                  <span class="text-muted"></span>
              </div>
              <div class="progress">
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
          </div>
      </div>
  </div>
  <div class="col-lg-4" style="margin-bottom:10px;">
      <div class="card" style="margin:0;">
          <div class="card-body">
              <h4 class="card-title">Expected ROI
              </h4>
              <div class="text-right">
                  <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                    {{$cur}}{{number_format($partner->total_expected_return(), 2, '.', ',')}}
                  </h2>
                  <span class="text-muted"></span>
              </div>
              <div class="progress">
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
          </div>
      </div>
  </div>
  <div class="col-lg-4" style="margin-bottom:10px;">
      <div class="card" style="margin:0;">
          <div class="card-body">
              <h4 class="card-title">Received ROI
              </h4>
              <div class="text-right">
                  <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                    {{$cur}}{{number_format($partner->total_received_roi(), 2, '.', ',')}}
                  </h2>
                  <span class="text-muted"></span>
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
                <h4 class="card-title">Total Received
                </h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{$cur}}{{number_format($partner->credited, 2, '.', ',')}}
                    </h2>
                    <span class="text-muted"></span>
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
                <h4 class="card-title">Current Balance
                  @if($partner->balance >= $partner->min_with)
                     &nbsp;
                    <button class="btn btn-sm btn-primary" data-toggle='modal' data-target='#cashout'>Cashout</button>
                  @endif
                </h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{$cur}}{{number_format($partner->balance, 2, '.', ',')}}
                    </h2>
                    <span class="text-muted"></span>
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
                <h4 class="card-title">Total Withdrawn</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{$cur}}{{number_format($partner->debited, 2, '.', ',')}}
                    </h2>
                    <span class="text-muted"></span>
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
 <div class="card-body">
    <div class="d-flex m-b-40 align-items-center no-block">
        <h5 class="card-title">CONTRACTS</h5>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
         <tr>
           <th>No</th>
           <th>Amount Invested</th>
           <th>Total Return</th>
           <th>Total Returned</th>
           <th>Status</th>
           <th>Created</th>
           <th>Duration</th>
         </tr>
      </thead>
      <tbody>
        @if($contracts->count())
           @php $count = Helpers::tableNumber(10) @endphp
           @foreach($contracts as $contract)
             <tr>
               <td>{{$count++}}</td>
               <td>{{$cur.number_format($contract->amount)}}</td>
               <td>{{$cur.number_format($contract->total_return)}}</td>
               <td>{{$cur.number_format($contract->returned)}}</td>
               <td>
                 @if($contract->status == 1)
                   <span class="badge badge-success">Completed</span>
                 @elseif($contract->status == 0)
                   <span class="badge badge-warning">Active</span>
                 @elseif($contract->status == 2)
                   <span class="badge badge-danger">Expired</span>
                 @endif
               </td>
               <td>
                 <div>{{$contract->created_at->isoFormat('lll')}}</div>
                 {{$contract->created_at->diffForHumans()}}
               </td>
               <td>
                 @if($contract->months)
                   <div>{{$contract->months}} Months</div>
                   {{$contract->created_at->addMonths($contract->months)->isoFormat('lll')}}
                   @if(Carbon::now() >= $contract->created_at->addMonths($contract->months))
                     <div><small class="text-danger">Expired</small></div>
                   @endif
                 @else
                   <span class="badge badge-success">Unlimited</span>
                 @endif
               </td>
             </tr>
           @endforeach
        @endif
      </tbody>
    </table>
  </div>
  {{$contracts->links()}}
  </div>
</div>
<script type="text/javascript">
   onReady(function(){});
</script>
@endsection

@extends('user.layout', ['title'=>'Trading History'])
@php
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
  <div class="row">
      <div class="col-lg-4">
          <div class="card" style="margin:0;">
              <div class="card-body">
                  <h4 class="card-title">Active Trades</h4>
                  <div class="text-right">
                      <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                        {{$cur.number_format($tActive, 2, '.', ',')}}
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
                  <h4 class="card-title">Total Received</h4>
                  <div class="text-right">
                      <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                        {{$cur.number_format($tReceived, 2, '.', ',')}}
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
                  <h4 class="card-title">Total Traded</h4>
                  <div class="text-right">
                      <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                          {{$cur.number_format($tTraded, 2, '.', ',')}}
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
  <div class="text-right">
      <a href="{{route('user.trading.index')}}" class="btn btn-primary">Select Plans</a>
  </div>
  <br>
  <div class="card">
    <div class="card-header" style="background:#eee;">
       <h4 class="card-title">Trading History</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
       <table class="table table-bordered table-hover">
         <thead>
            <tr>
              <th>No</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Returned</th>
              <th>Interest</th>
              <th>Status</th>
              <th>Created</th>
              <th>Expiry Date</th>
            </tr>
         </thead>
         <tbody>
           @if($trades->count())
             @php $count = Helpers::tableNumber(20) @endphp
             @foreach($trades as $trade)
               <tr>
                 <td>{{$count++}}</td>
                 <td>{{ucwords($trade->name)}} Plan</td>
                 <td>{{$cur.number_format($trade->amount)}}</td>
                 <td>{{$cur.number_format($trade->returned, 2, ',', '.')}}</td>
                 <td>{{$trade->interest}}%</td>
                 <td>
                   @if($trade->status == 1)
                     <span class="badge badge-success">Complete</span>
                   @else
                     <span class="badge badge-warning">Trading</span>
                   @endif
                 </td>
                 <td>{{$trade->created_at->isoFormat('lll')}}</td>
                 <td>{{$trade->created_at->addDays($trade->exp_days)->isoFormat('lll')}}</td>
               </tr>
             @endforeach
          @endif
         </tbody>
       </table>
     </div>
       @if(!$trades->count())
          <div class="alert alert-warning">
            Nothing to show
          </div>
       @endif
       {{$trades->links()}}
    </div>
  </div>
@endsection

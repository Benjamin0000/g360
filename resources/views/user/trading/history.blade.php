@extends('user.layout', ['title'=>'Trading History'])
@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
  {{-- <div class="card">
    <div class="card-body text-center">
        <h3>Earn more by trading with us.</h3>
        <small>Choose your trading package</small>
    </div>
  </div> --}}
  <div class="text-right">
      <a href="{{route('user.trading.index')}}" class="btn btn-primary">Plans</a>
  </div>
  <br>
  <div class="card">
    <div class="card-header" style="background:#eee;">
       <h4 class="card-title">Trading History</h4>
    </div>
    <div class="card-body">
       <table class="table">
         <thead>
            <tr>
              <th>No</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Returned</th>
              <th>Interest</th>
              <th>Status</th>
              <th>Expiry Date</th>
            </tr>
         </thead>
         <tbody>
           @if($trades->count())
             @php $count = Helpers::tableNumber($total) @endphp
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
                 <td>{{$trade->created_at->addDays($trade->exp_days)}}</td>
               </tr>
             @endforeach
          @endif
         </tbody>
       </table>
       @if(!$trades->count())
          <div class="alert alert-warning">
            Nothing to show
          </div>
       @endif
    </div>
  </div>
@endsection

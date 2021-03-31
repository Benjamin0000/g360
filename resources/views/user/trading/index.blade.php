@extends('user.layout', ['title'=>'Trading'])
@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
  <style media="screen">
    .ttb tr{border-top: hidden;}
  </style>
  <div class="card">
    <div class="card-body text-center">
        <h3>Earn more by trading with us.</h3>
        <small>Choose your trading package</small>
    </div>
  </div>
  <div class="text-right">
      <a href="{{route('user.trading.history')}}" class="btn btn-primary btn-sm">History</a>
  </div>
  <div class="row">
    @if($packages->count())
      @foreach($packages as $package)
        <div class="col-md-4">
          <div class="card">
            <div class="card-body text-center">
              <h4>{{strtoupper($package->name.' Plan')}} </h4>
              <table class="table ttb">
                  <tr><td>Amount</td> <td><b>{{$cur.number_format($package->amount)}}</b></td> </tr>
                  <tr><td>Period</td> <td><b>{{$package->exp_days/30}} months</b></td></tr>
                  <tr><td>ROI</td> <td><b>{{$package->interest}}%</b></td></tr>
              </table>
              <form action="{{route('user.trading.selectPkg', $package->id)}}" method="post">
                @csrf
                <button class="btn btn-outline-success">Choose</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    @endif
  </div>
@endsection

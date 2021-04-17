@extends('user.layout', ['title'=>'Rewards'])
@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
@if(count($rewards))
  @foreach ($rewards as $reward)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3 class="card-title mb-4 text-info">{{ucwords($reward->name)}} Reward</h3>
                </div>
                <div class="col-lg-6 col-md-6 border-top">
                    <div class="card-body">
                       <div class="text-center">
                          <h3 class="font-medium">Monthly Leadership Bonus</h3>
                          <h6 class="font-medium">{{$cur.number_format(($reward->lmp_amount/$reward->lmp_month))}} For {{$reward->lmp_month}} months</h6>
                       </div>
                       <form  action="{{route('user.reward.lmp', $reward->id)}}" method="post">
                         @csrf
                         <div class="text-center">
                           <button class="btn btn-success"><i class="ti-plus"></i> Claim</button>
                         </div>
                       </form>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6  border-top pt-1 pl-0">
                    <div class="text-center">
                        <h3 class="font-medium">Loan</h3>
                        <h6>{{$cur.number_format($reward->loan_amount)}} payable within {{$reward->loan_month}} months</h6>
                        <form  action="{{route('user.reward.loan', $reward->id)}}" method="post">
                          @csrf
                          <button class="btn btn-primary"><i class="fas fa-angle-double-right"></i> Request Loan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  @endforeach
@else
  <div class="card">
      <div class="card-body">
          <br/>
          <h5 class="text-center">No rewards yet.</h5>
          <br/>
      </div>
  </div>
@endif
@endsection

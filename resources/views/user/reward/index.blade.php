@extends('user.layout', ['title'=>'Rewards'])
@php
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
@if(count($new_rewards))
  @foreach ($new_rewards as $new_reward)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3 class="card-title mb-4 text-info">{{ucwords($new_reward->name)}} Reward</h3>
                </div>
                <div class="col-lg-6 col-md-6 border-top">
                    <div class="card-body">
                       <div class="text-center">
                          <h3 class="font-medium">Monthly Leadership Bonus</h3>
                          <h6 class="font-medium">{{$cur.number_format(($new_reward->lmp_amount/$new_reward->lmp_month))}} For {{$new_reward->lmp_month}} months</h6>
                       </div>
                       <form  action="{{route('user.reward.lmp', $new_reward->id)}}" method="post">
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
                        <h6>{{$cur.number_format($new_reward->loan_amount)}} payable within {{$new_reward->loan_month}} months</h6>
                        <form  action="{{route('user.reward.loan', $new_reward->id)}}" method="post">
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
        <br>
        <h5 class="text-center">No rewards yet.</h5>
        <br>
    </div>
</div>

<div class="card">
    <div class="card-body">
      <h3 class="card-title">History</h3>
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-stylish">
          <thead>
             <tr>
               <th>No</th>
               <th>Name</th>
               <th>Loan</th>
               <th>LMP</th>
               <th>Status</th>
               <th>Claimed</th>
               <th>Created</th>
             </tr>
          </thead>
          <tbody>
            @if($rewards->count())
              @php $count = Helpers::tableNumber(10) @endphp
              @foreach($rewards as $reward)
                <tr>
                  <td>{{$count++}}</td>
                  <td>{{$reward->name}}</td>
                  <td>{{$cur.number_format($reward->loan_amount)}}</td>
                  <td>{{$cur.number_format($reward->lmp_amount)}}</td>
                  <td>
                    @if($reward->status == 1)
                      <span class="badge badge-success">Claimed</span>
                    @else
                      <span class="badge badge-warning">pending</span>
                    @endif
                  </td>
                  <td>{{$reward->selected}}</td>
                  <td>{{$reward->created_at->isoFormat('lll')}}</td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
        {{$rewards->links()}}
      </div>
    </div>
</div>
@endif
@endsection

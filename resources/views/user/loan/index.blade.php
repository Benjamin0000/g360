@extends('user.layout', ['title'=>'Loan'])
@php
use Carbon\Carbon;
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')

@if($loanRequests->count())
  <div class="card">
      <div class="card-body">
        <h4 class="text-center">Loan Request</h4>
        <div class="table-responsive">
          <table  class="table">
             <thead>
               <tr>
                 <th>User</th>
                 <th>Amount</th>
                 <td>Created</td>
                 <th>Action</th>
               </tr>
             </thead>
             <tbody>
                @foreach($loanRequests as $loanRequest)
                  <tr>
                    <td>
                      {{$loanRequest->user->fname.' '.$loanRequest->user->lname}}
                    </td>
                    <td>{{$cur.number_format($loanRequest->amount)}}</td>
                    <td>
                      <div>{{$loanRequest->created_at->isoFormat('lll')}}</div>
                      {{$loanRequest->created_at->diffForHumans()}}
                    </td>
                    <td>
                       <form action="{{route('user.loan.approve', $loanRequest->id)}}" method="post" style="display:inline">
                         @csrf
                         <input type="hidden" name="type" value="approve">
                         <button class="btn btn-success btn-sm">Accept</button>
                       </form>
                       <form action="{{route('user.loan.approve', $loanRequest->id)}}" method="post" style="display:inline">
                         @csrf
                         <input type="hidden" name="type" value="disapprove">
                         <button class="btn btn-danger btn-sm">Reject</button>
                       </form>
                    </td>
                  </tr>
                @endforeach
             </tbody>
          </table>
        </div>
      </div>
  </div>
@endif


@if($active_loan)
<div class="card">
    <div class="card-body">
      <h4 class="text-center">Active Loan</h4>
        <ul class="list-icons">
            <li><b>Paid Amount: {{$cur.$active_loan->returned}}</b><span class="badge badge-success"></span></li>
            <li><b>Balance: {{$cur.($active_loan->total_return - $active_loan->returned)}}</b><span class="badge badge-danger mr-2"></span></li>
            @if($active_loan->grace_date)
              <li><b>Elapse Date: {{Carbon::parse($active_loan->grace_date)->isoFormat('lll')}}</b></li>
            @else
              <li><b>Elapse Date: {{Carbon::parse($active_loan->expiry_date)->isoFormat('lll')}}</b></li>
            @endif
            <li>
              <form action="{{route('user.loan.pay')}}" method="post">
                @csrf
                <button class="btn btn-primary" name="button">Pay now</button>
              </form>
            </li>
          </ul>
    </div>
</div>
@endif


<div class="row">
  <div class="col-lg-12">
    <div class="float-right">
      <a href="{{route('user.loan.apply')}}" class="btn btn-primary"><i class="mdi mdi-plus-circle"></i> Loan Application</a>
    </div>
    <br>
    <br>
      <div class="card" style="clear:right;">
          <div class="card-body">
              <div class="d-flex no-block">
                  <h4 class="card-title">Loan History</h4>
              </div>
              <div class="table-responsive">
                  <table class="table table-bordered stylish-table">
                      <thead>
                          <tr>
                            <th>No</th>
                            <th>Amount</th>
                            <th>Elapse Date</th>
                            <th>Status</th>
                            <th>Approved</th>
                            <th>Extra</th>
                            <th>Created</th>
                          </tr>
                      </thead>
                      <tbody>
                        @if($loans->count())
                          @php $count = Helpers::tableNumber(10) @endphp
                          @foreach($loans as $loan)
                            <tr>
                              <td>{{$count++}}</td>
                              <td>{{$cur.number_format($loan->amount)}}</td>
                              <td>
                                @if($loan->grace_date)
                                  {{Carbon::parse($loan->grace_date)->isoFormat('lll')}}
                                @else
                                  {{Carbon::parse($loan->expiry_date)->isoFormat('lll')}}
                                @endif
                              </td>
                              <td>
                                @if($loan->status == 1)
                                  <span class="text-warning">Completed</span>
                                @else
                                  @if($loan->garant)
                                    <span class="text-warning">Pending</span>
                                  @else 
                                    <span class="text-warning">Active</span>
                                  @endif
                                @endif
                              </td>
                              <td>
                                @if($loan->garant)
                                  @if($loan->g_approve == 1)
                                    Yes <span class="fa fa-check-circle text-success"></span>
                                  @elseif($loan->g_approve == 2)
                                    Rejected <span class="fa fa-cancel text-danger"></span>
                                  @else
                                    ---
                                  @endif
                                @else
                                  Yes <span class="fa fa-check-circle text-success"></span>
                                @endif
                              </td>
                              <td>
                                {{$loan->extra}}
                              </td>
                              <td>
                                {{$loan->created_at->isoFormat('lll')}}
                              </td>
                            </tr>
                          @endforeach
                        @endif
                      </tbody>
                  </table>
              </div>
              {{$loans->links()}}
          </div>
      </div>
  </div>
</div>
@endsection

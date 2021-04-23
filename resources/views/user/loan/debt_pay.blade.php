@extends('user.layout', ['title'=>'Loan Debt'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<div class="card">
    <div class="card-header text-center" style="background:#eee;">
        Loan Debt
    </div>
    <div class="card-body">
      <div class="text-center">
        @php
         $debt = $loan->total_return - $loan->returned;
        @endphp
        <h2>{{$cur.number_format($debt, 2,'.', ',')}}</h2>
      </div>
      @if($loan->defaulted == 0)
        <div class="row">
          <div class="col-md-6">
            <h4 class="text-center">Extend Loan with a {{$loan->grace_interest}}% interest for {{$loan->grace_months}}months</h4>
            <form action="{{route('user.loan.loanExtend', $loan->id)}}" method="POST">
              @csrf
              <div class="text-center">
                <button class='btn btn-primary'>Extend Loan</button>
              </div>
            </form>
          </div>
          <div class="col-md-6">
            <form action="{{route('user.loan.pay')}}" method="POST">
              @csrf
              <div class="text-center">
                <button class='btn btn-primary'>Pay Loan</button>
              </div>
            </form>
          </div>
        </div>
      @else
      <form action="{{route('user.loan.pay')}}" method="POST">
        @csrf
        <div class="text-center">
          <button class='btn btn-primary'>Pay Loan</button>
        </div>
      </form>
      @endif
    </div>
</div>
@endsection

@extends('user.layout', ['title'=>'Loan Debt'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<div class="card">
    <div class="card-header text-center">
        Loan Debt
    </div>
    <div class="card-body">
      <div class="text-center">
        @php
         $debt = $loan->total_return - $loan->returned; 
        @endphp
        <h2>{{$cur.number_format($debt, 2,'.', ',')}}</h2>
      </div>
      <div class="row">
        <div class="col-md-6">
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
    </div>
</div>
@endsection

@extends('user.layout', ['title'=>'Apply For Loan'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
$min_loan = 50000;
@endphp
@section('content')
  <div class="card">
      <div class="card-header text-center">
          Apply for Loan
      </div>
      <div class="card-body">
          <center>
              <img src="/assets/images/loan.png?>" width="120px" />
          </center>
          <div class="text-center">
            <h4 class="card-title">Apply For a Loan</h4>
            <p class="card-text">Get low interest loan without collateral</p>
          </div>
          <form class="form" method="post" action="{{route('user.loan.requestLoan')}}">
            @if($user->loan_elig_balance < $min_loan)
              <div class="form-group">
                  <label for="example-text-input">Guarantor G-No.</label>
                  <input required  class="form-control" name="gno_1" type="text" placeholder="G - Number" value="" >
              </div>
            @else
              <input type="hidden" name="nn" value="1">
            @endif
              <div class="form-group">
                  <label for="example-month-input2">Amount</label>
                  <input required type="number"id="lamt" name="amount" value="" class="form-control">
              </div>
              <div class="form-group">
                <label for="example-month-input2">Period</label>
                <select class="custom-select col-12" name="period" id="period">
                    <option value="">Select interval...</option>
                </select>
              </div>
              <div class="form-group">
                  <label for="example-text-input" name="name">Extra information</label>
                  <textarea  class="form-control" type="text" name="extra"></textarea>
              </div>
              @csrf
              <div class="form-group mt-5">
                  <button class="btn btn-outline-primary float-right">Submit request </button>
              </div>
          </form>
      </div>
  </div>
  <script type="text/javascript">
     onReady(function(){
        $('#lamt').on('keyup', function(){
            var amt = $(this).val();
            if(amt >= 50000 && amt <= 500000){
               $('#period').empty().append("<option value='6' selected>6 months (10%)</option>");
            }else if(amt > 500000 && amt <= 2000000){
               $('#period').empty().append("<option value='12' selected>12 months (10%)</option>");
            }else if(amt > 2000000 && amt <= 5000000){
               $('#period').empty().append("<option value='24' selected>24 months (10%)</option>");
            }else{
              $('#period').empty();
            }
        });
     });
  </script>
@endsection

@extends('user.layout', ['title'=>'Apply For A Loan'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
$min_loan = App\Models\LoanSetting::orderBy('min', 'ASC')->first()->min;
$settings = App\Models\LoanSetting::orderBy('min', 'ASC')->get();
@endphp
@section('content')
<style media="screen">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
-webkit-appearance: none;
margin: 0;
}
input[type=number] {
-moz-appearance: textfield;
}
.show_details{
  display:none;
}
</style>
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
                <label for="example-month-input2">Range</label>
                <select class="custom-select col-12" name="range" id="period">
                    <option value="">Select interval...</option>
                    @if($settings->count())
                      @foreach($settings as $setting)
                        <option value="{{$setting->exp_months}}">{{$cur.number_format($setting->min)}} To {{$cur.number_format($setting->max)}}</option>
                      @endforeach
                    @endif
                </select>
              </div>
              @if($settings->count())
                @foreach($settings as $setting)
                  <div class="form-group show_details" id="show_details{{$setting->exp_months}}">
                     <small><b>Details</b></small>
                     <div>Min: {{$cur.number_format($setting->min)}}</div>
                     <div>Max: {{$cur.number_format($setting->max)}}</div>
                     <div>Period: {{$setting->exp_months}}Months</div>
                     <div>Interest: {{$setting->interest}}%</div>
                  </div>
                @endforeach
              @endif
              <div class="form-group">
                  <label for="example-month-input2">Amount</label>
                  <input required inputmode="numeric" type="text" name="amount" value="" class="form-control">
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
        $('#period').on('change', function(){
            var val = $(this).val();
            if(val != ''){
              $('.show_details').hide();
              $("#show_details"+val).show();
            }else{
              $('.show_details').hide();
            }
        });
     });
  </script>
@endsection

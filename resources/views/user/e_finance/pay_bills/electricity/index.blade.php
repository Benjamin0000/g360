@extends('user.layout', ['title'=>'Pay Bills'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Electricity Bill</h3>
    </div>
    <div class="card-body">
      <p class="">
        <a class="btn btn-primary btn-sm" href="{{route('user.efinance.index')}}">Go Back</a>
      </p>
       <form action="{{route('user.pay_bills.elect.buy')}}" method="post" id="electf">
           <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                     <label for="">Disco Type</label>
                     <select class="form-control" name="disco" id="disco">
                       <option> WHAT IS YOUR DISCO TYPE ? </option>
                    </select>
                 </div>
                 @csrf
                 <div class="form-group">
                   <label for="">Amount</label>
                   <input type="text" class="form-control" name="amount" value="">
                 </div>
                 <div class="form-group">
                     <label for="">Meter Number</label>
                     <input type="text" class="form-control" name="meter_number" value="">
                 </div>
                 <div class="form-group">
                     <button class="btn btn-primary">Continue</button>
                 </div>
               </div>
           </div>
       </form>
    </div>
</div>
<script type="text/javascript">
</script>
@endsection

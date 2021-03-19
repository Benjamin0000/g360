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
        <a class="btn btn-primary btn-sm" href="{{route('user.pay_bills.index')}}">Go Back</a>
        <a class="btn btn-primary btn-sm" href="#" >View History</a>
      </p>
       <form action="" method="post" id="electf">
         <div class="row">
             <div class="col-md-6">
               <div class="form-group">
                   <label for="">Disco Type</label>
                   <select class="form-control" name="disco" id="disco">
                     <option> WHAT IS YOUR DISCO TYPE ? </option>
                     <option value="aedc">Abuja Electricity Distribution Company (AEDC)</option>

                     <option value="phed">Port Harcourt Electricity Distribution Company (PHED)</option>
                     <option value="kedco">Kano Electricity Distribution Company (KEDCO)</option>
                     <option value="jec">Jos Electricity Distribution Company (JED)</option>

                     <option value="ekedc">Eko Electricity Distribution Company (EKEDC)</option>
                     <option value="kedc">kaduna electricity distribution company (KEDC)</option>
                  </select>
               </div>
             </div>
             <div class="col-md-6">
               <div class="form-group">
                  <label for="">Meter Type</label>
                  <select class="form-control" name="meter">
                     <option value="">Choose</option>
                     <option value="">Prepaid</option>
                     <option value="">Pospaid</option>
                  </select>
                </div>
             </div>
         </div>
         <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="">Meter Number</label>
                  <input type="text" class="form-control" name="mnumber" value="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Amount</label>
                <input type="text" class="form-control" name="" value="">
              </div>
            </div>
         </div>
         <div class="form-group">
             <button class="btn btn-primary">Continue</button>
         </div>
       </form>
    </div>
</div>
<script type="text/javascript">
onReady(function(){
  $('#electf').on('submit', function(e){
    e.preventDefault();

  });
});
</script>
@endsection

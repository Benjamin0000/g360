@extends('user.layout', ['title'=>'Cable TV Subscription'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Cable TV Subscription</h3>
    </div>
    <div class="card-body">
       <form class="" action="" method="post">
           <div class="form-group">
               <label for="">Select Provider</label>
               <select class="form-control" name="">

               </select>
           </div>
           <div class="form-group">
              <label for="">Smart card number</label>
              <input type="text" class="form-control" name="" value="">
           </div>
           <div class="form-group">
             <label for="">Select Package</label>
             <select class="form-control" name="">

             </select>
           </div>
           <div class="form-group">
             <label for="">Receivers Phone number</label>
               <input type="text" class="form-control" name="" value="">
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

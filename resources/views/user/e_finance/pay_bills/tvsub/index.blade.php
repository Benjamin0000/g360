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
      <p class="">
        <a class="btn btn-primary btn-sm" href="{{route('user.efinance.index')}}">Go Back</a>
      </p>
      <div id="tvs">
       <form id="tvsf">
           <div class="form-group">
               <label for="">Select Provider</label>
               <select class="form-control" name="provider" id="provider">
                 <option value="">Select</option>
                 @if($providers->count())
                   @foreach($providers as $provider)
                     <option value="{{$provider->id}}">{{$provider->name}}</option>
                   @endforeach
                 @endif
               </select>
           </div>
           @csrf
          <div id="plan"></div>
           <div class="form-group">
              <label for="">Smart card number</label>
              <input type="text" class="form-control" name="smart_card" value="">
           </div>
           {{-- <div class="form-group">
             <label for="">Receivers Phone number</label>
               <input type="text" class="form-control" name="receivers_mobile" value="">
           </div> --}}
           <div id="error"></div>
           <div class="form-group">
             <button class="btn btn-primary" id="tvbb">Continue</button>
           </div>
       </form>
     </div>
    </div>
</div>
<script type="text/javascript">
onReady(function(){
  $('#provider').on('change', function(){
    var loader = "<div class='text-center'><div class='lds-ripple'><div></div><div></div></div></div>";
    $('#plan').html(loader);
     $.ajax({
       type:'get',
       url:"{{route('user.pay_bills.tvSub.plans')}}/"+$(this).val(),
       timeout:8000,
       success:function(data){
         $('#plan').html(data);
       },
       error:function(xmlhttprequest, textstatus, message){
            if(textstatus==="timeout") {
                $('#plan').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Endpoint taking too long please select another provider</div>");
            } else {
              $('#plan').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Cannot proceed</div>");
            }
       }
     });
  });
  $('#tvsf').on('submit', function(e){
      e.preventDefault();
      var $ele = $('#tvbb');
      $ele.data('text',$ele.text());
      $ele.html(get_loader());
      $ele.prop('disabled',true);
      $.ajax({
        url:"{{route('user.pay_bills.validateTvAcc')}}",
        type:'post',
        data:$(this).serialize(),
        success:function(data){


        },
        error:function(){
          $('#error').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> could not complete</div>");
        }
      });
  });
});
</script>
@endsection

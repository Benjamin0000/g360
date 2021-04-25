@extends('user.layout', ['title'=>'Cable TV Subscription'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<div class="card" style="min-height:30vh;margin:0;">
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
               <select class="form-control" required name="provider" id="provider">
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
              <input type="text" required class="form-control" name="smart_card" value="">
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
<div class="card">
    <div class="card-body">
      <h3 class="card-title">History</h3>
      <div class="table-responsive">
         <table class="table table-bordered table-hover stylish-table">
           <thead>
            <tr>
              <th>No</th>
              <th>Amount</th>
              <th>Provider</th>
              <th>Type</th>
              <th>Date</th>
            </tr>
           </thead>
           <tbody>
            @if($histories->count())
              @php $count = Helpers::tableNumber(10); @endphp
              @foreach($histories as $history)
                <tr>
                  <td>{{$count++}}</td>
                  <td>
                    {{$cur.number_format($history->amount, 2, '.', ',')}}
                    <div>{{$history->description}}</div>
                  </td>
                  <td>{{$history->service}}</td>
                  <td>{{ucwords($history->type)}}</td>
                  <td>{{$history->created_at->isoFormat('lll')}}</td>
                </tr>
              @endforeach
            @endif
           </tbody>
         </table>
      </div>
      {{$histories->links()}}
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
       timeout:20000,
       success:function(data){
         $('#plan').html(data);
       },
       error:function(xmlhttprequest, textstatus, message){
          if(textstatus==="timeout") {
              $('#plan').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Provider taking too long try again</div>");
          }else{
            $('#plan').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Cannot proceed</div>");
          }
       }
     });
  });
  $('#tvsf').on('submit', function(e){
     var er = "<div class='alert alert-danger'><i class='fa fa-info-circle'></i> could not complete</div>";
      e.preventDefault();
      $('#error').html('');
      var $ele = $('#tvbb');
      $ele.data('text',$ele.text());
      $ele.html(get_loader());
      $ele.prop('disabled',true);
      $.ajax({
        url:"{{route('user.pay_bills.validateTvAcc')}}",
        type:'post',
        timeout:10000,
        data:$(this).serialize(),
        success:function(data){
          if(data.error){
            $("#error").html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> "+data.error+"</div>");
          }else if(data.status){
            $("#tvs").html(data.status);
          }else{
              $('#error').html(er);
          }
        },
        error:function(xmlhttprequest, textstatus, message){
          if(textstatus==="timeout") {
              $('#error').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Invalid smartcard number</div>");
          }else{
            $('#error').html(er);
          }
          $ele.text($ele.data('text'));
          $ele.prop('disabled',false);
        }
      });
  });
});
</script>
@endsection

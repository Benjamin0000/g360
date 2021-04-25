@extends('user.layout', ['title'=>'Pay Bills'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<div class="card" style="min-height:80vh;margin:0;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Electricity Bill</h3>
    </div>
    <div class="card-body">
      <p class="">
        <a class="btn btn-primary btn-sm" href="{{route('user.efinance.index')}}">Go Back</a>
      </p>
      <div id="main_fff">
       <form action="{{route('user.pay_bills.elect.buy')}}" method="post" id="electf">
           <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                     <label for="">Disco Type</label>
                     <select class="form-control" name="disco" id="disco" required>
                       <option value=""> WHAT IS YOUR DISCO TYPE ? </option>
                       @if($discos->count())
                         @foreach($discos as $disco)
                           <option value="{{$disco->code}}">{{$disco->name}}</option>
                         @endforeach
                       @endif
                    </select>
                 </div>
                 @csrf
                 <div class="form-group">
                   <label for="">Amount</label>
                   <input type="number" id='anum' class="form-control" name="amount" value="" required>
                 </div>
                 <input type="hidden" name="type" value="1">
                 <div class="form-group">
                     <label for="">Meter Number</label>
                     <input type="text" id="mn" class="form-control" name="meter_number" value="" required>
                 </div>
                 <div id="mE"></div>
                 <div class="form-group">
                     <button class="btn btn-primary" id="mdbtn">Continue</button>
                 </div>
               </div>
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
              <th>Disco</th>
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
  $("#electf").on('submit', function(e){
    e.preventDefault();
    var $ele = $('#mdbtn');
    $ele.data('text',$ele.text());
    $ele.html(get_loader());
    $ele.prop('disabled',true);
    $('#mE').html("");
    $.ajax({
      url:"{{route('user.pay_bills.elect.buy')}}",
      type:"POST",
      data:$(this).serialize(),
      success:function(data){
        if(data.status){
          $('#main_fff').html(data.status);
        }else if(data.error){
          $('#mE').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> "+data.error+"</div>");
        }else{
          $('#mE').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Request could not be processed</div>");
        }
        $ele.text($ele.data('text'));
        $ele.prop('disabled',false);
      },
      error:function(){
        $('#mE').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Request could not be processed</div>");
        $ele.text($ele.data('text'));
        $ele.prop('disabled',false);
      }
    });
  });
});
</script>
@endsection

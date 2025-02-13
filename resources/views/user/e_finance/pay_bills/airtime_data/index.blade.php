@extends('user.layout', ['title'=>'Airtime & Data Subscription'])
@php
use App\Http\Helpers;
use Carbon\Carbon;
$cur = Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<style media="screen">
.tab{
  box-shadow: 0 0 2px rgb(0 0 0 / 16%), 0 1px 9px rgb(0 0 0 / 8%);
  background:#fbfbfb !important;
}
.tab .card-header{
  background:#fbfbfb !important;
  color:#666;
  font-weight: bold;
  cursor: pointer;
  min-height: 70px;
  line-height: 50px;
}
.tab .card-header span{
  font-size: 25px;
}
.form-control{
  box-shadow: none !important;
  font-size:14px;
}
.fc{
  padding:10px;
}
.cur{
  font-size:14px;
}
.n-img{
width: 24%;
cursor: pointer;
}
.n-img-a{
  border:1px solid black;
}
</style>
<div class="card" style="min-height:30vh;margin:0;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Airtime & Data Subscription</h3>
    </div>
    <div class="card-body">
      <div class="">
        <a href="{{route('user.efinance.index')}}" class="btn btn-primary btn-sm">Go back</a>
      </div>
      <br>
      <div class="row">
          <div class="col-md-6">
              <div class="card tab">
                  <div class="card-header" data-toggle="collapse" href="#collapse1">
                    Buy Airtime <span class="float-right mdi mdi-chevron-down"></span>
                  </div>
                  <div id="collapse1" class="card-body collapse">
                    @include('user.e_finance.pay_bills.airtime_data.a_form')
                  </div>
              </div>
          </div>
          <div class="col-md-6">
              <div class="card tab">
                  <div class="card-header" data-toggle="collapse" href="#collapse2">
                    Buy Mobile Data <span class="float-right mdi mdi-chevron-down"></span>
                  </div>
                  <div id="collapse2" class="card-body collapse">
                    @include('user.e_finance.pay_bills.airtime_data.d_form')
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
      <h3 class="card-title">HISTORY</h3>
      <div class="table-responsive">
         <table class="table table-bordered stylish-table">
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
  $('.tab .card-header').on('click', function(){
      var caret = $(this).children()[0];
      if($(caret).hasClass('mdi-chevron-down')){
        $(caret).removeClass('mdi-chevron-down');
        $(caret).addClass('mdi-chevron-up');
      }else{
        $(caret).removeClass('mdi-chevron-up');
        $(caret).addClass('mdi-chevron-down');
      }
  });
  $('.n-img').on('click', function(){
      $('.n-img').removeClass('n-img-a');
      $(this).addClass('n-img-a');
  });
  $('#electf').on('submit', function(e){
    e.preventDefault();
  });
  $('#dnum').on('input', function (){
      $('#data_form .n-img').removeClass('n-img-a');
  });
});
</script>
@endsection

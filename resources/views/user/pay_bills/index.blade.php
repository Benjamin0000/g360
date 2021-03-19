@extends('user.layout', ['title'=>'Pay Bills'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
  <style media="screen">
      .bill-l{
        display:inline-block;
        background:#eee;
        text-decoration: none;
        color:#555 !important;
        font-size: 20px;
        padding:10px;
        width: 200px;
        margin:10px;
        text-align: center;
        transition: 0.3s ease;
      }
      .bill-l:hover{
        background:#ccc;
      }
  </style>
  <div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Pay Bills</h3>
    </div>
    <div class="card-body text-center">
      <a href="{{route('user.pay_bills.airtimeData.index')}}"  class="bill-l"><i class="fas fa-phone-square-alt text-primary"></i> Airtime / Data</a>
      <a href="{{route('user.pay_bills.elect.index')}}"  class="bill-l"><i class="fas fa-bolt text-danger"></i> Electricity</a>
      <a href="{{route('user.pay_bills.waterSub.index')}}"  class="bill-l"><i class="fas fa-shower"></i> Water</a>
      <a href="{{route('user.pay_bills.tvSub.index')}}"  class="bill-l"><i class="fas fa-tv text-warning"></i> Cable Tv</a>
    </div>
  </div>
  <script type="text/javascript">
     onReady(function(){});
  </script>
@endsection

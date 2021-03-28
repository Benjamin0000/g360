@extends('user.layout', ['title'=>'GSTeam'])
@section('content')
@php
use Carbon\Carbon;
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@if(!$member)
<div class="alert alert-danger">
  <i class="fa fa-info-circle"></i> Purchase a premium package to opt in for GSTeam
</div>
@endif
<div class="card" style="margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>GSTeam</h3>
  </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">GST-Balance
                  @if($member && $member->wbal > 0)
                    <button class="btn btn-primary btn-sm blink">Cashout</button>
                  @endif
                </h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{$cur}}{{$member?number_format($member->wbal):0}}
                    </h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="margin:0;">
            <div class="card-body">
                <h4 class="card-title">R-Balance</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="mdi mdi-wallet text-success"></i>
                      {{$cur}}@if($member && $member->g){{number_format($member->gbal)}}@else{{0}}@endif
                    </h2>
                    <span class="text-muted">Current Balance</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card" style="min-height:80vh;margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>Transaction History</h3>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
           <tr>
             <th>No</th>
             <th>Amount</th>
             <th>Description</th>
             <th>Created</th>
           </tr>
        </thead>
        <tbody id="tbodyt">
          @include('user.gsclub.table_tr')
        </tbody>
      </table>
    </div>
    @if($total_his >= 10)
      <div class="text-center">
         <button class="btn btn-primary btn-sm" p='2' id='mbtn'>Load more</button>
      </div>
    @endif
  </div>
</div>
<script type="text/javascript">
  onReady(function(){
     $('#mbtn').on('click', function(){
       var $ele = $(this);
       var page = parseInt($ele.attr('p'));
       $ele.data('text',$ele.text());
       $ele.html(get_loader());
       $ele.prop('disabled',true);
       $.ajax({
         'type':'get',
          url:"{{route('user.gsclub.morehis')}}?page="+page,
          success:function(data){
             if(data.data){
               page+=1;
               $ele.attr('p', page);
               $('#tbodyt').append(data.data);
               $ele.text($ele.data('text'));
               $ele.prop('disabled',false);
             }else{
               $ele.text($ele.data('text'));
             }
          },
          error:function(){
            $ele.text($ele.data('text'));
            $ele.prop('disabled',false);
          }
       });
     });
  });
</script>
@endsection

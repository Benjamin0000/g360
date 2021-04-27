@extends('admin.layout', ['title'=>'GSTEAM'])
@section('content')
@php
  use Carbon\Carbon;
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style>tr{text-align:center;}</style>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title float-left">GsTeam Fee</h5>
              <br>
                <div class="">
                    <div class="float-right">
                      <a href="{{route('admin.dashboard.index')}}" class=" float-right btn btn-info btn-sm">Back</a>
                    </div>
                </div>
                <div class="clear:right">
                    <h3 class="text-center">{{$cur.number_format($gsteam_fee, 2, '.', ',')}}</h3>
                      <button class="btn btn-sm btn-info" data-toggle='modal' data-target='#debit'>Debit</button>
                      <br>
                      <br>
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>NO</th>
                            <th>Amount</th>
                            <th>Purpose</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                           @if($histories->count())
                             @php $count = Helpers::tableNumber(10) @endphp
                             @foreach($histories as $history)
                               <tr>
                                 <td>{{$count++}}</td>
                                 <td>{{$cur.number_format($history->amount, 2, '.', ',')}}</td>
                                 <td>{{$history->description}}</td>
                                 <td>
                                   {{$history->created_at->isoFormat('lll')}}
                                   <div>{{$history->created_at->diffForHumans()}}</div>
                                 </td>
                               </tr>
                             @endforeach
                           @endif
                        </tbody>
                      </table>
                  </div>
                  {{$histories->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal"  id="debit">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">DEBIT GSTEAM FEE</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form  action="{{route('admin.debitGST')}}" method="post">
           <div class="form-group">
              <input required type="text" name="amount" placeholder="Amount" value="" class="form-control">
           </div>
           <div class="form-group">
              <textarea required name="purpose" placeholder="Purpose" class="form-control"></textarea>
           </div>
           @csrf
           <button class="btn btn-success btn-block">Deduct</button>
        </form>
        </div>
      </div>
  </div>
</div>
@endsection

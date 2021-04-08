@extends('admin.layout', ['title'=>'PARTNERS CASHOUTS'])
@section('content')
@php
use App\Http\Helpers;
use Carbon\Carbon;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style>tr{text-align: center;}</style>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">CASHOUTS</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
          <a href="{{route('admin.partner.index')}}"  class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">ALL CASHOUTS</h5>
                </div>
                <div style="min-height:340px;">
                  <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                       <tr>
                         <th>No</th>
                         <th>Name</th>
                         <th>Amount</th>
                         <th>Status</th>
                         <th>Created</th>
                         <th>Action</th>
                       </tr>
                    </thead>
                    <tbody>
                      @if($cashouts->count())
                         @php $count = Helpers::tableNumber(10) @endphp
                         @foreach($cashouts as $cashout)
                           <tr>
                             <td>{{$count++}}</td>
                             <td>{{$cashout->user->fname.' '.$cashout->user->lname}}</td>
                             <td>{{$cur.number_format($cashout->amount)}}</td>
                             <td>
                                @if($cashout->status == 1)
                                  <span class="badge badge-success">Completed</span>
                                @else
                                  <span class="badge badge-danger">Pending</span>
                                @endif
                             </td>
                             <td>
                               {{$cashout->created_at->isoFormat('lll')}}
                               <div>{{$cashout->created_at->diffForHumans()}}</div>
                             </td>
                             <td>
                                <form action="{{route('admin.partner.processCashout', $cashout->id)}}" method="post">
                                  @csrf
                                  @method('patch')
                                  <button class="btn btn-success btn-sm">Process</button>
                                </form>
                             </td>
                           </tr>
                         @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
                 {{$cashouts->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
                <div class="">
                    <h5 class="card-title float-left">GSTEAM</h5>
                    <div class="float-right">
                      <a href="{{route('admin.gsteam.settings')}}" class=" float-right btn btn-info btn-sm">Settings</a>
                    </div>
                </div>
                <div class="table-responsive">
                 <table class="table table-bordered table-hover">
                   <thead>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Wbal</th>
                        <th>G-bal</th>
                        <th>Type</th>
                        <th>Last give</th>
                        <th>Last Receive</th>
                        <th>Status</th>
                        <th>Created</th>
                      </tr>
                   </thead>
                   <tbody>
                     @if($gsclubs->count())
                       @php $count = Helpers::tableNumber(10) @endphp
                       @foreach($gsclubs as $gsclub)
                         <tr>
                           <td>{{$count++}}</td>
                           <td>{{$gsclub->user->fname.' '.$gsclub->user->lname}}</td>
                           <td>{{$cur.number_format($gsclub->wbal)}}</td>
                           <td>{{$cur.number_format($gsclub->gbal)}}</td>
                           <td>
                             @if($gsclub->g)
                               <span class="badge badge-success">Giving</span>
                             @else
                               <span class="badge badge-warning">Receiving</span>
                             @endif
                           </td>
                           <td>{{Carbon::parse($gsclub->lastg)->diffForHumans()}}</td>
                           <td>{{Carbon::parse($gsclub->lastr)->diffForHumans()}}</td>
                           <td>
                             @if($gsclub->status == 1)
                               <span class="badge badge-success">Completed</span>
                             @else
                                <span class="badge badge-warning">Active</span>
                             @endif
                           </td>
                           <td>{{$gsclub->created_at->diffForHumans()}}</td>
                         </tr>
                       @endforeach
                     @endif
                   </tbody>
                 </table>
               </div>
               {{$gsclubs->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

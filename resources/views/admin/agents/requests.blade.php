@extends('admin.layout', ['title'=>'AGENTS'])
@section('content')
@php
use App\Http\Helpers;
@endphp
<style>tr{text-align:center;}</style>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">AGENT REQUEST</h5>
                </div>
                  <div class="table-responsive">
                   <table class="table table-bordered table-hover">
                     <thead>
                        <tr>
                          <th>No</th>
                          <th>Name</th>
                          <th>More info</th>
                          <th>Region</th>
                          <th>Created</th>
                          <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                       @if($agents->count())
                         @php $count = Helpers::tableNumber(10) @endphp
                         @foreach($agents as $agent)
                           <tr>
                             <td>{{$count++}}</td>
                             <td>{{ucwords($agent->user->fname.' '.$agent->user->lname)}}</td>
                             <td>
                                @include('admin.agents.info_modal')
                               <button class="btn btn-primary btn-sm" data-toggle='modal' data-target='#info{{$agent->id}}'>INFO</button>
                             </td>
                             <td>
                               <div>{{Helpers::getStateById($agent->state_id)}},</div>
                               <div>{{Helpers::getStateById($agent->city_id)}}</div>
                             </td>
                             <td>{{$agent->created_at->diffForHumans()}}</td>
                             <td>
                               <form action="{{route('admin.agents.approve', $agent->id)}}" method="post" style="display:inline;">
                                 @csrf
                                 @method('patch')
                                 <button class="btn btn-info btn-sm">Approve</button>
                               </form>
                               <form action="{{route('admin.agents.disapprove', $agent->id)}}" method="post" style="display:inline;">
                                 @csrf
                                 @method('delete')
                                 <button class="btn btn-danger btn-sm" onclick="return confirm('are you sure about this?')">Delete</button>
                               </form>
                             </td>

                           </tr>
                         @endforeach
                       @endif
                     </tbody>
                   </table>
                 </div>
                 {{$agents->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layout', ['title'=>'CONTRACTS'])
@section('content')
@php
use App\Http\Helpers;
use Carbon\Carbon;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style>tr{text-align: center;}</style>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">CONTRACTS FOR {{$partner->user->fname.' '.$partner->user->lname}}</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
          <a href="{{route('admin.partner.index')}}"  class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-arrow-left"></i> Back</a>
            <a href="#" data-toggle='modal' data-target='#create' class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>Create contract</a>
        </div>
    </div>
</div>
@include('admin.partners.contract.create_modal')
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">ALL CONTRACTS</h5>
                </div>
                <div style="min-height:340px;">
                  <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                       <tr>
                         <th>No</th>
                         <th>Name</th>
                         <th>Amount Invested</th>
                         <th>Total Return</th>
                         <th>Total Returned</th>
                         <th>Status</th>
                         <th>Created</th>
                         <th>Duration</th>
                         <th>Action</th>
                       </tr>
                    </thead>
                    <tbody>
                      @if($contracts->count())
                         @php $count = Helpers::tableNumber(10) @endphp
                         @foreach($contracts as $contract)
                           <tr>
                             <td>{{$count++}}</td>
                             <td>
                               {{$contract->partner->user->fname.' '.$contract->partner->user->lname}}
                               <div>{{$contract->partner->gnumber}}</div>
                               @if($contract->partner->type == 1)
                                 <span class="badge badge-success">Super</span>
                               @else
                                 <span class="badge badge-danger">Sub</span>
                               @endif
                             </td>
                             <td>{{$cur.number_format($contract->amount)}}</td>
                             <td>{{$cur.number_format($contract->total_return)}}</td>
                             <td>{{$cur.number_format($contract->returned)}}</td>
                             <td>
                               @if($contract->status == 1)
                                 <span class="badge badge-success">Completed</span>
                               @elseif($contract->status == 0)
                                 <span class="badge badge-warning">Active</span>
                               @elseif($contract->status == 2)
                                 <span class="badge badge-danger">Expired</span>
                               @endif
                             </td>
                             <td>
                               <div>{{$contract->created_at->isoFormat('lll')}}</div>
                               {{$contract->created_at->diffForHumans()}}
                             </td>
                             <td>
                               @if($contract->months)
                                 <div>{{$contract->months}} Months</div>
                                 {{$contract->created_at->addMonths($contract->months)->isoFormat('lll')}}
                                 @if(Carbon::now() >= $contract->created_at->addMonths($contract->months))
                                   <div><small class="text-danger">Expired</small></div>
                                 @endif
                               @else
                                 <span class="badge badge-success">Unlimited</span>
                               @endif
                             </td>
                             <td>
                               {{-- <button class="btn btn-info btn-sm" data-toggle='modal' data-target='#credit{{$contract->id}}'>Credit</button> --}}
                               {{-- <button class="btn btn-sm btn-primary" data-toggle='modal' data-target='#edit{{$contract->id}}'>Edit</button> --}}
                               <form  action="{{route('admin.pcontract.delete', $contract->id)}}" method="post" style="display:inline;">
                                 @csrf
                                 @method('delete')
                                 <button class="btn btn-sm btn-danger" onclick="return confirm('are you sure about this?')">Delete</button>
                               </form>
                             </td>
                           </tr>
                             {{-- @include('admin.contracts.edit_modal') --}}
                             {{-- @include('admin.contracts.credit_modal') --}}
                         @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
                 {{$contracts->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

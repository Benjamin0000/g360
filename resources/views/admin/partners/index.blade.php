@extends('admin.layout', ['title'=>'PARTNERS'])
@section('content')
@php
use App\Http\Helpers;
use Carbon\Carbon;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style>tr{text-align:center;}</style>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">PARTNERS</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.partner.cashout')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-money"></i>Cashouts</a>
            <a href="#" data-toggle='modal' data-target='#create' class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>Create Partner</a>
        </div>
    </div>
</div>
@include('admin.partners.create_modal')
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-user"></i></h3>
                            <p class="text-muted">TOTAL PARTNERS</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-primary">{{number_format($total_partners)}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-note"></i></h3>
                            <p class="text-muted">AMOUNT INVESTED</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-cyan">{{$cur.number_format($amt_invested)}}</h2>
                            <div style="margin-top:-16px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-cyan" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-doc"></i></h3>
                            <p class="text-muted">EXPECTED RETURN</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-purple">{{$cur.number_format($ext_amt)}}</h2>
                            <div style="margin-top:-16px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-purple" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-bag"></i></h3>
                            <p class="text-muted">AMOUNT RECEIVED</p>
                        </div>
                        <div class="ml-auto">
                          <h2 class="counter text-purple">{{$cur.number_format($credited, 2, '.', ',')}}</h2>
                          <div style="margin-top:-16px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">ALL PARTNERS</h5>
                </div>
                <div style="min-height:340px;">
                  <div class="table-responsive">
                   <table class="table table-bordered table-hover">
                     <thead>
                        <tr>
                          <th>No</th>
                          <th>Name</th>
                          <th>Total Invested</th>
                          <th>Total Received</th>
                          <th>Total Debited</th>
                          <th>Current Balance</th>
                          <th>Created</th>
                          <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                       @if($partners->count())
                          @php $count = Helpers::tableNumber(10) @endphp
                          @foreach($partners as $partner)
                            <tr>
                              <td>{{$count++}}</td>
                              <td>
                                {{$partner->user->fname.' '.$partner->user->lname}}
                                <div>{{$partner->gnumber}}</div>
                                @if($partner->type == 1)
                                  <span class="badge badge-success">Super</span>
                                @else
                                  <span class="badge badge-danger">Sub</span>
                                @endif
                              </td>
                              <td>{{$cur.number_format($partner->total_invested())}}</td>
                              <td>{{$cur.number_format($partner->credited)}}</td>
                              <td>{{$cur.number_format($partner->debited)}}</td>
                              <td>{{$cur.number_format($partner->balance, 2, '.', ',')}}</td>
                              <td>{{$partner->created_at->diffForHumans()}}</td>
                              <td>
                                {{-- <button class="btn btn-info btn-sm" data-toggle='modal' data-target='#credit{{$partner->id}}'>Credit</button> --}}
                                <a href="{{route('admin.pcontract.index', $partner->id)}}" class="btn btn-sm btn-primary">Cont.({{$partner->contracts->count()}})</a>
                                <button class="btn btn-sm btn-primary" data-toggle='modal' data-target='#edit{{$partner->id}}'>Edit</button>
                                <form  action="{{route('admin.partner.delete', $partner->id)}}" method="post" style="display:inline;">
                                  @csrf
                                  @method('delete')
                                  <button class="btn btn-sm btn-danger" onclick="return confirm('are you sure about this?')">Delete</button>
                                </form>
                              </td>
                            </tr>
                              @include('admin.partners.edit_modal')
                              {{-- @include('admin.partners.credit_modal') --}}
                          @endforeach
                       @endif
                     </tbody>
                   </table>
                 </div>
                 {{$partners->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

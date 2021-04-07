@extends('admin.layout', ['title'=>'AGENTS'])
@section('content')
@php
use App\Http\Helpers;
use Carbon\Carbon;
$cur = Helpers::LOCAL_CURR_SYMBOL;
$states = config('states');
@endphp
<style>tr{text-align:center;}</style>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">AGENTS</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.agents.settings')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-settings"></i>Settings</a>
            <a href="{{route('admin.agents.new')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-money"></i>Requests ({{$new_requests}})</a>
            <a href="#" data-toggle='modal' data-target='#create' class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>Create Agent</a>
        </div>
    </div>
</div>
@include('admin.agents.create_agent_modal')
{{-- <div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-user"></i></h3>
                            <p class="text-muted">TOTAL AGENTS</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-primary"></h2>
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
                            <p class="text-muted">AMOUNT EARND</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-cyan"></h2>
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
                            <h2 class="counter text-purple"></h2>
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
                          <h2 class="counter text-purple"></h2>
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
</div> --}}
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">ALL AGENTS</h5>
                </div>
                  <div class="table-responsive">
                   <table class="table table-bordered table-hover">
                     <thead>
                        <tr>
                          <th>No</th>
                          <th>Name</th>
                          <th>FBronze Coin</th>
                          <th>GSiver Coin</th>
                          <th>RGold Coin</th>
                          <th>POSD Coin</th>
                          <th>Total</th>
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
                            <td>{{$count++}}</td>
                            <td>
                              <div>{{$agent->user->fname.' '.$agent->user->lname}}</div>
                              <div>{{$agent->user->gnumber}}</div>
                              @if($agent->type == 1)
                                  <span class="badge badge-success">Super</span>
                              @else
                                  <span class="badge badge-danger">Sub</span>
                              @endif
                            </td>
                            <td>{{number_format($agent->fbronz_coin(), 2, '.', ',')}}</td>
                            <td>{{number_format($agent->gsilver_coin(), 2, '.', ',')}}</td>
                            <td>{{number_format($agent->rgold_coin(), 2, '.', ',')}}</td>
                            <td>{{number_format($agent->posD_coin(), 2, '.', ',')}}</td>
                            <td>{{$cur.number_format($agent->totalBalance(),2, '.', ',')}}</td>
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
                              <form action="{{route('admin.agents.makeSuper', $agent->id)}}" method="post">
                                  @csrf
                                  @method('patch')
                                  @if($agent->type == 0)
                                    <button class="btn btn-sm btn-info">Make super</button>
                                  @else
                                    <button class="btn btn-danger btn-sm">Remove super</button>
                                  @endif
                              </form>
                            </td>
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

@extends('admin.layout', ['title'=>'VTU Settings'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title ">VTU Settings</h5>
                    <a href="{{route('admin.finance.vtu')}}" class="btn btn-info btn-sm">Back</a>
                </div>
                   <div class="row">
                      <div class="col-md-6">
                        <h4 class="text-center">Airtime</h4>
                        <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                              <thead>
                                <tr>
                                  <th>Network</th>
                                  <th>Min buy</th>
                                  <th>Max buy</th>
                                  <th>Comm.(%)</th>
                                  <th>Ref. Amount</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @if($airtimes->count())
                                  @foreach($airtimes as $airtime)
                                    <tr>
                                      <td>{{$airtime->name}} <img src="{{asset($airtime->logo)}}" alt="Logo" width="30">  </td>
                                      <td>{{$airtime->min_buy}}</td>
                                      <td>{{$airtime->max_buy}}</td>
                                      <td>{{$airtime->comm}}%</td>
                                      <td>{{$airtime->ref_amt}}</td>
                                      <td>
                                        <button class="btn btn-primary btn-sm" data-toggle='modal' data-target='#airtime{{$airtime->id}}'>Edit</button>
                                      </td>
                                      @include('admin.finance.vtu.settings.edit_air_modal')
                                    </tr>
                                  @endforeach
                                @endif
                              </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <h4 class="text-center">Data</h4>
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                  <tr>
                                    <th>Network</th>
                                    <th>Comm.(%)</th>
                                    <th>Ref. Amount</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @if($datasubs->count())
                                    @foreach($datasubs as $datasub)
                                      <tr>
                                        <td>{{$datasub->name}} <img src="{{asset($datasub->logo)}}" alt="Logo" width="30"></td>
                                        <td>{{$datasub->comm}}%</td>
                                        <td>{{$datasub->ref_amt}}</td>
                                        <td>
                                          <button class="btn btn-primary btn-sm" data-toggle='modal' data-target='#data{{$datasub->id}}'>Edit</button>
                                        </td>
                                        @include('admin.finance.vtu.settings.edit_data_modal')
                                      </tr>
                                    @endforeach
                                  @endif
                                </tbody>
                            </table>
                          </div>
                      </div>
                   </div>
            </div>
        </div>
    </div>
</div>
@endsection

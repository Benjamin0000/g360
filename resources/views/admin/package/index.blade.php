@extends('admin.layout', ['title'=>'PACKAGES'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style>tr{text-align:center;}</style>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">PACKAGES</h5>
                </div>
                <div class="table-responsive">
                 <table class="table table-bordered table-hover">
                   <thead>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>PV</th>
                        <th>H-Token</th>
                        <th>Ref. Pv</th>
                        <th>Ref. H-Token</th>
                        <th>Ref. Com. Percent</th>
                        <th>Insurance</th>
                        <th>Ref. level Gen.</th>
                        <th>Action</th>
                      </tr>
                   </thead>
                   <tbody>
                      @if($packages->count())
                        @php $count = 1 @endphp
                        @foreach($packages as $package)
                          <tr>
                            <td>{{$count++}}</td>
                            <td>{{ucwords($package->name)}}</td>
                            <td>{{$cur.number_format($package->amount)}}</td>
                            <td>{{$package->pv}}</td>
                            <td>{{$package->h_token}}</td>
                            <td>{{$package->ref_pv}}</td>
                            <td>{{$package->ref_h_token}}</td>
                            <td>{{$package->ref_percent}}</td>
                            <td>{{$package->insurance}} Months</td>
                            <td>{{$package->gen}}</td>
                            <td>
                              <button data-toggle='modal' data-target='#edit{{$package->id}}' class="btn btn-sm btn-info">EDIT</button>
                            </td>
                          </tr>
                            @include('admin.package.edit_modal')
                        @endforeach
                      @endif
                   </tbody>
                 </table>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection

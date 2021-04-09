@extends('admin.layout', ['title'=>'GSTEAM SETTINGS'])
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
              <div class="">
                  <h5 class="card-title float-left">GSTEAM SETTINGS</h5>
                  <div class="float-right">
                    <a href="{{route('admin.gsteam.index')}}" class=" float-right btn btn-info btn-sm">Back</a>
                  </div>
              </div>
                <div class="table-responsive">
                 <table class="table table-bordered table-hover">
                   <thead>
                      <tr>
                        <th>Level</th>
                        <th>Amount</th>
                        <th>Payback</th>
                        <th>Total Givers</th>
                        <th>Givers Hours</th>
                        <th>Receivers Days</th>
                        <th>Total Referrals</th>
                        <th>Action</th>
                      </tr>
                   </thead>
                   <tbody>
                     @if($gtrs->count())
                       @foreach($gtrs as $gtr)
                         <tr>
                           <td>{{$gtr->level}}</td>
                           <td>{{$cur.number_format($gtr->amount)}}</td>
                           <td>{{$cur.number_format($gtr->pay_back)}}</td>
                           <td>{{$gtr->r_count}}</td>
                           <td>{{$gtr->g_hours}}</td>
                           <td>{{$gtr->r_days}}</td>
                           <td>{{$gtr->total_ref}}</td>
                           <td>
                             <button  data-toggle='modal' class="btn btn-sm btn-info" data-target='#edit{{$gtr->id}}'>Edit</button>
                           </td>
                         </tr>
                         @include('admin.gsteam.settings.edit_modal')
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

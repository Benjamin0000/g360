@extends('admin.layout', ['title'=>'Default users'])
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
<style>tr{text-align:center;}</style>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title float-left">GSTEAM</h5>
                    <div class="float-right">
                     <button class="btn btn-sm btn-info" data-toggle='modal' data-target="#def{{$gtr->id}}">ADD</button> &nbsp;<a href="{{route('admin.gsteam.settings')}}" class=" float-right btn btn-info btn-sm">Back</a>
                    </div>
                    @include('admin.gsteam.settings.addDefaultUser_modal')
                </div>
                <div style="clear: right;">
                  <h3 class="text-center"><b>Level [ <b>{{$cur.number_format($gtr->amount)}}</b> ]</b> Default Users</h3>
                  <br>
                  @include('admin.gsteam.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
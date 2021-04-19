@extends('admin.layout', ['title'=>'GSTEAM'])
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
                      <a href="{{route('admin.gsteam.index')}}" class=" float-right btn btn-info btn-sm">Back</a>
                    </div>
                </div>
                <div style="clear: right;">
                  <h3 class="text-center"><b>Level [ <b>{{$cur.number_format($gtr->amount)}}</b> ] 
                    {{$type?"Givers": "Receivers"}}
                </b></h3>
                  <br>
                  @include('admin.gsteam.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

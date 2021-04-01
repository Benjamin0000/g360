@extends('admin.layout', ['title'=>'VTU'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">VTU</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.finance.vtu.settings')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="ti-settings"></i> Settings</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title ">HISTORY</h5>
                </div>
                <div style="min-height:340px;">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

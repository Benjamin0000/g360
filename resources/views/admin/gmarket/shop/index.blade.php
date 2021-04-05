@extends('admin.layout', ['title'=>'VTU'])
@section('content')
@php
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title ">SHOPS</h5>
                </div>
                <div style="min-height:340px;">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

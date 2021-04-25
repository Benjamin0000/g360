@extends('user.layout', ['title'=>'Banking'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
@section('content')
<div class="card" style="margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>Banking</h3>
  </div>
</div>
<div class="card" style="margin:0;">
  <div class="card-body">
    <p class="">
      <a class="btn btn-primary btn-sm" href="{{route('user.efinance.index')}}">Go Back</a>
    </p>
    <h3 class="text-center">Coming Soon</h3>
  </div>
</div>
@endsection

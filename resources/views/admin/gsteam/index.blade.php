@extends('admin.layout', ['title'=>'GSTEAM'])
@section('content')
@php
  use Carbon\Carbon;
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style>tr{text-align:center;}</style>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card" style="background: none;">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title float-left">GSTEAM</h5>
                    <div class="float-right">
                      <a href="{{route('admin.gsteam.settings')}}" class=" float-right btn btn-info btn-sm">Settings</a>
                    </div>
                </div>
                <div style="clear: right;">
                  <h3 class="text-center"><b>Levels</b></h3>
                  <br>
                 <div class="row">
                    @if($levels->count())
                      @foreach ($levels as $level)
                        <div class="col-lg-4">
                          <div class="card">
                            <div class="card-body">
                                <h3>{{$cur.number_format($level->amount)}}</h3>
                                <div>Givers: <a style="font-size:20px;" href="{{route('admin.gsteam.show', [$level->id, 1])}}">{{$level->gsTeamType($level->amount, 1)}}</a></div>
                                <div>Receivers: <a style="font-size:20px;" href="{{route('admin.gsteam.show', [$level->id, 0])}}">{{$level->gsTeamType($level->amount, 0)}}</a></div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

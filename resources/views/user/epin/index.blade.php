@extends('user.layout', ['title'=>'Epin'])
@php 
use App\Http\Helpers; 
$user = Auth::user();
@endphp
@section('content')
<div class=" clearfix row ">
    <div class="col-sm-12 p-3">
        <a href="{{route('user.epin.buy')}}" class="btn btn-outline-success float-right">Buy E-Pin</a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                Select E-pin category to proceed
            </div>
        </div>
    </div>
</div>
<!-- Row -->
<div class="row text-center">
    @if($packages->count())
        @foreach($packages as $package)
            @if($package->name != 'free')
                <div class="col-lg-4">
                    <div class="card">
                        <a href="{{route('user.epin.show', $package->name)}}">
                            <div class="card-body">
                                <div class="p-2">
                                    <h3 class="mb-0 text-center">
                                        {{$package->name == 'vip' ? 'VIP' : ucfirst($package->name) }} package Pin
                                    </h3>
                                </div>
                                <div class="d-flex align-items-center text-center flex-row mt-4">
                                    <div class="p-2 display-5 text-info">
                                        <span class="text-danger">
                                            {{Helpers::countUserEpin($user->id, 'used', $package->id)}}</span> / 
                                        <span class="text-info">
                                            {{Helpers::countUserEpin($user->id, 'open', $package->id)}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>
@endsection
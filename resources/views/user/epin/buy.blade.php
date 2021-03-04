@extends('user.layout', ['title'=>'Buy Epin'])
@php 
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
    <div class=" clearfix row ">
        <div class="col-sm-12 p-3">
            <a href="{{route('user.epin.index')}}" class="btn btn-outline-default float-right">Manage Epin</a>
        </div>
    </div>
    <!-- Row -->
    <div class="row text-center">
        @if($packages->count())
            @foreach($packages as $package)
                @if($package->name != 'free')
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="p-2">
                                    <h3 class="mb-0 text-center">
                                        {{$package->name == 'vip' ? 'VIP' : ucfirst($package->name)}} <i class="mdi mdi-account-check text-success"></i> 
                                    </h3>
                                </div>
                                <div class="d-flex align-items-center flex-row mt-4">
                                    <div class="p-2 display-5 text-info">
                                        {{$cur}}<span>{{number_format($package->amount)}}</span>
                                    </div>
                                    <div class="p-2">
                                        <small class="mb-0">per pin</small>
                                    </div>
                                </div>
                                <form method="post" action="{{route('user.epin.buy')}}">
                                    <div class="form-group">
                                        <input type="text" required class="form-control" name="number" placeholder="No of Pins" />
                                    </div>
                                    @csrf
                                    <input type="hidden" name="package" value="{{$package->id}}" />
                                    <div class="form-group">
                                        <button type="submit" class="btn float-right waves-effect waves-light btn-outline-success">Pay via T-wallet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
@endsection
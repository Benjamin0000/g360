@extends('user.layout', ['title'=>$pkg_name.' Package Epin'])
@php 
    use App\Http\Helpers;
    use Carbon\Carbon;
    $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
<div class=" clearfix row ">
    <div class="col-sm-12 p-3">
        <a href="{{route('user.epin.index')}}" class="btn btn-outline-default">Go Back</a>
        <a href="{{route('user.epin.buy')}}" class="btn btn-outline-success float-right">Buy Epin</a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">ALL <b>{{strtoupper($pkg_name)}}</b> PACKAGE E-PINS</h4>
                <div class="table-responsive">
                    <table class="table no-wrap datatable table-hover" id="table_id">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Pin</th>
                                <th>Package</th>
                                <th>Buy date</th>
                                <th>Used date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($epins->count())
                                @php $count = Helpers::tableNumber($total); @endphp
                                @foreach($epins as $epin)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$epin->code}} 
                                            @if(!$epin->status)
                                                <span class="badge badge-success">Available</span>
                                            @else 
                                                <span class="badge badge-danger">Used</span> 
                                            @endif
                                        </td>
                                        <td>{{$epin->package->name == 'vip' ? 'VIP' : ucfirst($epin->package->name)}}</td>
                                        <td>{{$epin->created_at->isoFormat('MMM Do YYYY')}}</td>
                                        <td>{{$epin->used_date ? Carbon::parse($epin->used_date)->isoFormat('MMM Do YYYY'): ''}}</td>
                                    </tr>
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
@extends('user.layout', ['title'=>'Indirect Downlines'])
@section('content')
@php
use App\Http\Helpers;
@endphp
<div class=" clearfix row ">
    <div class="col-sm-12 p-3">
        <a href="{{route('user.downline.direct')}}" class="btn btn-outline-default float-right">Direct Downline</a>
    </div>
</div>
<div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">LIST OF ALL INDIRECT DOWNLINE</h4>
                  <div class="table-responsive">
                    <table class="table no-wrap table-striped">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Package</th>
                                <th>Generation</th>
                                <th>Join date</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if($referals->count())
                            @php $count =  Helpers::tableNumber(10) @endphp
                            @foreach($referals as $referal)
                              <tr>
                                <td>{{$count++}}</td>
                                <td>{{$referal->fname.' '.$referal->lname}}</td>
                                <td>{{$referal->package->name}}</td>
                                <td>{{$referal['level']}}</td>
                                <td>{{$referal->created_at->isoFormat('lll')}}</td>
                              </tr>
                            @endforeach
                          @endif
                        </tbody>
                    </table>
                </div>
                {{$referals->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

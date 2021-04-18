@extends('user.layout', ['title'=>' Direct Downlines'])
@section('content')
@php
use App\Http\Helpers;
$user = Auth::user();
@endphp
<div class=" clearfix row ">
    <div class="col-sm-12 p-3">
        <a href="{{route('user.downline.indirect')}}" class="btn btn-outline-default float-right">Indirect Downline</a>
    </div>
</div>
<div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">LIST OF ALL DIRECT DOWNLINE</h4>
                  <div class="table-responsive">
                    <table class="table no-wrap table-striped">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Package</th>
                                <th>Join date</th>
                                <th>Activation date</th>
                                <th>PV</th>
                                <th>Percent</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if($referals->count())
                             @php $count = $count = Helpers::tableNumber(10) @endphp
                              @foreach($referals as $referal)
                                <tr>
                                  <td>{{$count++}}</td>
                                  <td>
                                    {{$referal->fname.' '.$referal->lname}}
                                    <div>{{$referal->gnumber}}</div>
                                  </td>
                                  <td>
                                    @if($referal->package)
                                      {{$referal->package->name == 'vip' ? 'VIP': ucfirst($referal->package->name)}}
                                    @else
                                      None
                                    @endif
                                  </td>
                                  <td>{{$referal->created_at->isoFormat('lll')}}</td>
                                  <td>{{$referal->upgrade ? $referal->upgrade->created_at->isoFormat('lll') : ''}}</td>
                                  <td>{{$referal->cpv}}</td>
                                  <td>{{$referal->refPercent($user->cpv)}}%</td>
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

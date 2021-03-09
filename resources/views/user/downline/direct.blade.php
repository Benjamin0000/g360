@extends('user.layout', ['title'=>' Direct Downlines'])
@section('content')
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
                            </tr>
                        </thead>
                        <tbody>
                          @if($referals->count())
                             @php $count = $count = tableNumber($total) @endphp
                              @foreach($referals as $referal)
                                <tr>
                                  <td>{{$count++}}</td>
                                  <td>{{$referal->fname.' '.$referal->lname}}</td>
                                  <td>{{$referal->package->name == 'vip' ? 'VIP': ucfirst($referal->package->name)}}</td>
                                  <td>{{$referal->created_at->isoFormat('lll')}}</td>
                                  <td>{{$referal->upgrade->created_at->isoFormat('lll')}}</td>
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

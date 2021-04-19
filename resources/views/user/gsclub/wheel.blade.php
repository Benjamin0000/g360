@extends('user.layout', ['title'=>'YOUR WHEEL'])
@section('content')
@php
use Carbon\Carbon;
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
$user = Auth::user();
@endphp
<div class="card" style="min-height:80vh;margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>YOUR GSTEAM WHEEL</h3>
  </div>
  <div class="card-body">
    <p>
      <a href="{{route('user.gsclub.index')}}" class="btn btn-primary">Back</a>
    </p>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
           <tr>
             <th>POSITION</th>
             <th>USERS</th>
             <th>AMOUNT</th>
             <th>ACTION</th>
             <th>Time</th>
           </tr>
        </thead>
        <tbody>
          @if($members->count())
            @php $count = Helpers::tableNumber(10) @endphp
            @foreach ($members as $member)
                <tr>
                    <td>{{$count++}}</td>
                    <td>
                        {{$member->user->fname.' '.$member->user->lname}}
                        <div>{{$member->user->gnumber}}</div>
                    </td>
                    <td>
                        {{$cur.number_format($member->gbal, 2, '.', ',')}}
                    </td>
                    <td>
                     @if($member->g)
                        <span class="badge badge-success">Giving</span>
                      @else
                        <span class="badge badge-warning">Receiving</span>
                      @endif
                    </td>
                    <td>
                        {{$member->created_at->isoFormat('lll')}}
                        <div>{{$member->created_at->diffForHumans()}}</div>
                    </td>
                </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
    {{$members->links()}}
  </div>
</div>
@endsection

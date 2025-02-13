@php
use App\Http\Helpers;
@endphp
@if($histories->count())
  @php $count = Helpers::tableNumber(10) @endphp
  @foreach ($histories as $history)
    <tr>
       <td>{{$count++}}</td>
       <td>
         {{$cur.number_format($history->amount)}}
         @if($history->type != 0)
            <span class="badge badge-danger">Sent</span>
         @else
            <span class="badge badge-success">Received</span>
         @endif
       </td>
       <td>{{$history->description}}</td>
       <td>
         {{$history->created_at->isoFormat('lll')}}
         <div>{{$history->created_at->diffForHumans()}}</div>
        </td>
    </tr>
  @endforeach
@endif

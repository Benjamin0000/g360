@php
  use Carbon\Carbon;
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
         <tr>
           <th>No</th>
           <th>Name</th>
           <th>Total ref.</th>
           <th>Wbal</th>
           <th>G-bal</th>
           <th>Type</th>
           <th>Last give</th>
           <th>Last Receive</th>
           <th>Status</th>
           <th>Created</th>
         </tr>
      </thead>
      <tbody>
        @if($gsclubs->count())
          @php $count = Helpers::tableNumber(10) @endphp
          @foreach($gsclubs as $gsclub)
            <tr>
              <td>
                {{$count++}}
                @if($gsclub->def)
                  <div>
                    <button data-toggle='modal' data-target='#editdefaultuser{{$gsclub->id}}' class="btn btn-sm btn-primary">Edit</button>
                  </div>
                @endif
              </td>
              <td>
                {{$gsclub->user->fname.' '.$gsclub->user->lname}}
                <div>{{$gsclub->user->gnumber}}</div>
                @if($gsclub->def)
                <div><b>Tag:</b> <span class="text-info">{{$gsclub->tag}}</span></div>
                @endif
              </td>
              <td>
                {{$gsclub->user->totalValidRef()}}
                @if($gsclub->def)
                  @php 
                    $tr = 0;
                    $formular = json_decode('['.$gsclub->def_refs.']', true);
                  if( isset($formular[$gtr->id -1]) ){
                    $tr = $formular[$gtr->id -1];
                  }
                  @endphp 
                  <div>{{$tr}}</div>
                @endif
              </td>
              <td>{{$cur.number_format($gsclub->wbal)}}</td>
              <td>
                {{$cur.number_format($gsclub->gbal)}}
                @if(!$gsclub->g)
                  <div>X {{$gsclub->r_count}}</div>
                @endif
              </td>
              <td>
                @if($gsclub->g)
                  <span class="badge badge-success">Giving</span>
                @else
                  <span class="badge badge-warning">Receiving</span>
                @endif
                @if($gsclub->def)
                  <div>
                    <span class="badge badge-success">Default</span>
                  </div> 
                @endif
              </td>
              <td>{{Carbon::parse($gsclub->lastg)->diffForHumans()}}</td>
              <td>{{Carbon::parse($gsclub->lastr)->diffForHumans()}}</td>
              <td>
                @if($gsclub->status == 1)
                  <span class="badge badge-success">Completed</span>
                @else
                   <span class="badge badge-warning">Active</span>
                @endif
              </td>
              <td>{{$gsclub->created_at->diffForHumans()}}</td>
            </tr>
            @include('admin.gsteam.settings.Edit_default_user_modal')
          @endforeach
        @endif
      </tbody>
    </table>
  </div>
  {{$gsclubs->links()}}
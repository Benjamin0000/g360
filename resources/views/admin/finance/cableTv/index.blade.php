@extends('admin.layout', ['title'=>'Cable TV'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Cable TV</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.finance.cableTv.settings')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="ti-settings"></i> Settings</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title ">HISTORY</h5>
                </div>
                <div class="table-responsive">
                   <table class="table table-bordered table-hover stylish-table">
                     <thead>
                      <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Provider</th>
                        <th>Date</th>
                      </tr>
                     </thead>
                     <tbody>
                      @if($histories->count())
                        @php $count = Helpers::tableNumber(10); @endphp
                        @foreach($histories as $history)
                          <tr>
                            <td>{{$count++}}</td>
                            <td>{{$history->user->fname.' '.$history->user->lname}}</td>
                            <td>
                              {{$cur.number_format($history->amount, 2, '.', ',')}}
                              <div>{{$history->description}}</div>
                            </td>
                            <td>{{$history->service}}</td>
                            <td>{{$history->created_at->isoFormat('lll')}}</td>
                          </tr>
                        @endforeach
                      @endif
                     </tbody>
                   </table>
                </div>
                {{$histories->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

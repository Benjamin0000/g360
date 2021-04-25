@extends('admin.layout', ['title'=>'LOAN'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">LOAN</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.finance.loanSettings')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="ti-settings"></i> Settings</a>
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
                <table class="table table-hover table-bordered">
                  <thead>
                    <th>No</th>
                    <th>Amount</th>
                    <th>Elapse Date</th>
                    <th>Status</th>
                    <th>Approved</th>
                    <th>Extra</th>
                    <th>Created</th>
                  </thead>
                  <tbody>
                    @if($loans->count())
                      @php $count = Helpers::tableNumber(10) @endphp
                      @foreach($loans as $loan)
                        <tr>
                          <td>{{$count++}}</td>
                          <td>{{$cur.number_format($loan->amount)}}</td>
                          <td>
                            @if($loan->grace_date)
                              {{Carbon::parse($loan->grace_date)->isoFormat('lll')}}
                            @else
                              {{Carbon::parse($loan->expiry_date)->isoFormat('lll')}}
                            @endif
                          </td>
                          <td>
                            @if($loan->status == 1)
                              <span class="text-warning">Completed</span>
                            @else
                              @if($loan->garant)
                                 @if($loan->g_approve == 0)
                                   <span class="text-warning">Pending</span>
                                 @elseif($loan->g_approve == 1)
                                   <span class="text-warning">Active</span>
                                 @else
                                  ---
                                 @endif
                               @else
                                  <span class="text-warning">Active</span>
                               @endif
                            @endif
                          </td>
                          <td>
                            @if($loan->garant)
                              @if($loan->g_approve == 1)
                                Yes <span class="fa fa-check-circle text-success"></span>
                              @elseif($loan->g_approve == 2)
                                Rejected <span class="fa fa-cancel text-danger"></span>
                              @else
                                ---
                              @endif
                            @else
                              Yes <span class="fa fa-check-circle text-success"></span>
                            @endif
                          </td>
                          <td>
                            {{$loan->extra}}
                          </td>
                          <td>
                            {{$loan->created_at->isoFormat('lll')}}
                            <div>{{$loan->created_at->diffForHumans()}}</div>
                          </td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
                {{$loans->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

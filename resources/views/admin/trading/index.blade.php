@extends('admin.layout', ['title'=>'Trading'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Trading</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.trading.package')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>Packages</a>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-user"></i></h3>
                            <p class="text-muted">TOTAL PACKAGES</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-primary">{{$total_pkg}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-note"></i></h3>
                            <p class="text-muted">TOTAL TRADES</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-cyan">{{$cur.number_format($alltrade->sum('amount'), 2, '.', ',')}}</h2>
                            <div style="margin-top:-16px;">{{number_format($alltrade->count())}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-cyan" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-doc"></i></h3>
                            <p class="text-muted">COMPLETED TRADES</p>
                        </div>
                        <div class="ml-auto">
                            <h2 class="counter text-purple">{{$cur.number_format($completed->sum('amount'), 2, '.', ',')}}</h2>
                            <div style="margin-top:-16px;">{{number_format($completed->count())}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-purple" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h3><i class="icon-bag"></i></h3>
                            <p class="text-muted">ONGOING TRADES</p>
                        </div>
                        <div class="ml-auto">
                          <h2 class="counter text-purple">{{$cur.number_format($ongoing->sum('amount'), 2, '.', ',')}}</h2>
                          <div style="margin-top:-16px;">{{number_format($ongoing->count())}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
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
                <div style="min-height:340px;">
                  <div class="table-responsive">
                   <table class="table table-bordered table-hover">
                     <thead>
                        <tr>
                          <th>No</th>
                          <th>Type</th>
                          <th>Amount</th>
                          <th>Returned</th>
                          <th>Interest</th>
                          <th>Status</th>
                          <th>Created</th>
                          <th>Expiry Date</th>
                        </tr>
                     </thead>
                     <tbody>
                       @if($trades->count())
                         @php $count = Helpers::tableNumber(10) @endphp
                         @foreach($trades as $trade)
                           <tr>
                             <td>{{$count++}}</td>
                             <td>{{ucwords($trade->name)}} Plan</td>
                             <td>{{$cur.number_format($trade->amount)}}</td>
                             <td>{{$cur.number_format($trade->returned, 2, ',', '.')}}</td>
                             <td>{{$trade->interest}}%</td>
                             <td>
                               @if($trade->status == 1)
                                 <span class="badge badge-success">Complete</span>
                               @else
                                 <span class="badge badge-warning">Trading</span>
                               @endif
                             </td>
                             <td>{{$trade->created_at->diffForHumans()}}</td>
                             <td>{{$trade->created_at->addDays($trade->exp_days)}}</td>
                           </tr>
                         @endforeach
                      @endif
                     </tbody>
                   </table>
                 </div>
                   @if(!$trades->count())
                      <div class="alert alert-warning">
                        Nothing to show
                      </div>
                   @endif
                   {{$trades->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

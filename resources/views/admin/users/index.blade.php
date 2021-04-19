@extends('admin.layout', ['title'=>'USERS'])
@section('content')
@php
    use App\Http\Helpers;
    $cur = Helpers::LOCAL_CURR_SYMBOL;
    $last_pkg = App\Models\Package::orderBy('id', 'DESC')->first();
@endphp
    <style>tr{text-align:center;}</style>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex m-b-40 align-items-center no-block">
                        <h5 class="card-title">USERS</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Rank</th>
                                <th>Package</th>
                                <th>Total Refs.</th>
                                <th>Balance</th>
                                <th>Contact</th>
                                <th>Joined</th>
                            </tr>
                            </thead>
                            <tbody>
                              @if($users->count())
                                  @php $count = Helpers::tableNumber(10) @endphp
                                  @foreach($users as $user)
                                    <tr>
                                      <td>{{$count++}}</td>
                                      <td>
                                        {{$user->fname.' '.$user->lname}}
                                        <div>{{$user->gnumber}}</div>
                                      </td>
                                      <td>{{$user->rank ? ucwords($user->rank->name): 'Associate'}}</td>
                                      <td>{{$user->package->id == $last_pkg->id ? strtoupper($last_pkg->name): ucfirst($user->package->name)}}</td>
                                      <td>
                                        <div class="text-success">{{$user->totalNotPlaced()}}</div> 
                                        <div class="text-danger">{{$user->totalPlaced()}}</div>
                                    </td>
                                      <td style="font-size:15px;font-weight:bold;text-align:left;">
                                        <div>With Bal: {{$cur.number_format($user->with_balance, 2, '.', ',')}}</div> 
                                        <div>TRX Bal: {{$cur.number_format($user->trx_balance, 2, '.', ',')}}</div>
                                        <div>PEND Bal: {{$cur.number_format($user->pend_balance, 2, '.', ',')}}</div>
                                        <div>PKG Bal: {{$cur.number_format($user->pkg_balance, 2, '.', ',')}}</div>
                                        <div>Award Point: {{$cur.number_format($user->award_point, 2, '.', ',')}}</div>
                                        <div>CPV: {{$user->cpv}}</div>
                                        <div>H-TOKEN: {{$user->h_token}}</div>
                                      </td>
                                      <td>
                                        {{$user->phone}}
                                        <div>{{$user->email}}</div>
                                      </td>
                                      <td>
                                          {{$user->created_at->isoFormat('lll')}}
                                          <div>{{$user->created_at->diffForHumans()}}</div>
                                        </td>
                                    </tr>
                                  @endforeach
                              @endif
                            </tbody>
                        </table>
                    </div>
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

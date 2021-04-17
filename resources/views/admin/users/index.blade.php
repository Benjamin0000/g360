@extends('admin.layout', ['title'=>'USERS'])
@section('content')
    @php
        use App\Http\Helpers;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
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
                                      <td>

                                      </td>
                                      <td>
                                        {{$user->phone}}
                                        <div>{{$user->email}}</div>
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

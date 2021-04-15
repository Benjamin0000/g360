@extends('user.layout', ['title'=>'Packages'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
               <i class="fa fa-info-circle"></i> Your account is due for upgrade
            </div>
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center text-success">UPGRADE</h2>
                    <h3 class="text-center">{{ucwords($package->name)}}</h3>
                    <div class="row h-100 justify-content-center align-items-center">
                      <div class="col-md-6">
                        <form method="post" action="{{route('user.autoupgrade')}}">
                            <div class="form-group text-center">
                                <div><label for="">Sign up for Health Insurance?</label></div>
                                <div class="form-control">
                                    <label>Yes <input value="yes" checked type="radio" name="h" ></label> &nbsp; &nbsp;
                                    <label>No <input  value='no' type="radio" name="h" ></label>
                                </div>
                            </div>
                            @csrf
                            <div class="form-group text-center">
                               <button class="btn btn-success">Continue</button>
                            </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

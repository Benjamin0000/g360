@extends('user.layout', ['title'=>'Settings'])
@section('content')
@php
 $user = Auth::user();
@endphp
<div class="card" style="margin:0px;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>SETTINGS</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  {{-- <div class="text-center">
                      <img src="/assets/images/access.gif" width="200px" />
                      <h4 class="card-title">Password Control</h4>
                      <h6 class="card-subtitle">Change account password</h6>
                  </div> --}}
                  <div class="row">
                    <div class="col-md-6">
                      <h3>Password Settings</h3>
                      <form class="form pt-3" method="post" action="{{route('user.settings.pass')}}">
                          <div class="form-group">
                              <label for="exampleInputEmail1">New password</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">
                                          <i class="ti-email"></i>
                                      </span>
                                  </div>
                                  <input type="password" required name="password" class="form-control" placeholder="New password">
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="pwd1">Confirm Password</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">
                                          <i class="ti-lock"></i>
                                      </span>
                                  </div>
                                  <input type="password" required name="password_confirmation"  class="form-control" id="pwd1" placeholder="Confirm password">
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="pwd2">Current Password</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">
                                          <i class="ti-lock"></i>
                                      </span>
                                  </div>
                                  <input type="password" required name="current_password"  class="form-control" id="pwd2" placeholder="Current password">
                              </div>
                          </div>
                          @csrf
                          <button class="btn btn-success">Save changes</button>
                      </form>
                    </div>
                    <div class="col-md-6">
                       <form action="{{route('user.settings.nin')}}" method="post">
                          <div class="form-group">
                            <label for="">Enter NIN number</label>
                            <input type="text" required name="nin_number" value="{{$user->nin_number}}" class="form-control">
                          </div>
                          @csrf
                          <div class="form-group">
                            <button class="btn btn-success">Save Settings</button>
                          </div>
                       </form>
                    </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection

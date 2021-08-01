@extends('user.layout', ['title'=>'Profile'])
@section('content')
@php
$user = Auth::user();
@endphp
<div class="card" style="margin:0px;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>PROFILE</h3>
  </div>
  <div class="card-body">
    <div class="row">
                        <!-- Column -->
                        <div class="col-md-12 col-lg-4 col-xlg-3">
                            <div class="card">
                                <div class="card-body">
                                    <center class="mt-4"> <img src="" class="img-circle" width="150" />
                                        <h4 class="card-title mt-2">{{$user->fname.' '.$user->lname}}</h4>
                                        <h6 class="card-subtitle">
                                          {{$user->rank ? ucwords($user->rank->name): 'Associate'}}
                                        </h6>
                                    </center>
                                </div>
                                <div>
                                    <hr> </div>
                                <div class="card-body"> <small class="text-muted">Email address </small>
                                    <h6>{{$user->email}}</h6>
                                    <small class="text-muted p-t-30 db">Phone</small>
                                    <h6>{{$user->phone}}</h6>
                                    <small class="text-muted p-t-30 db">BVN</small>
                                    <h6>{{$user->virtualAccount ? $user->virtualAccount->bvn : ''}}</h6>
                                    <small class="text-muted p-t-30 db">Virtual Account number</small>
                                    <h6>{{$user->virtualAccount ? $user->virtualAccount->number : ''}}</h6> 
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                        <div class="col-md-12 col-lg-8 col-xlg-9">
                            <div class="card">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#settings" role="tab">INFO</a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings" role="tabpanel">
                                        <div class="card-body">

                                                <div class="form-group">
                                                    <label class="col-md-12">Full Name</label>
                                                    <div class="col-md-12">
                                                        <input type="text" placeholder="{{$user->fname.' '.$user->lname}}" disabled class="form-control form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="example-email" class="col-md-12">Email</label>
                                                    <div class="col-md-12">
                                                        <input placeholder="{{$user->email}}" disabled class="form-control form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="example-email" class="col-md-12">Phone</label>
                                                    <div class="col-md-12">
                                                        <input placeholder="{{$user->phone}}" disabled class="form-control form-control-line">
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group">
                                                    <label for="example-email" class="col-md-12">Address</label>
                                                    <div class="col-md-12">
                                                        <input placeholder="address" disabled class="form-control form-control-line">
                                                    </div>
                                                </div> --}}
                                                <div class="form-group">
                                                    <label for="example-email" class="col-md-12">Gender</label>
                                                    <div class="col-md-12">
                                                        <input placeholder="{{$user->title == 'mr' ? 'male' : 'female'}}" disabled class="form-control form-control-line">
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group">
                                                    <label for="example-email" class="col-md-12">Date of Birth</label>
                                                    <div class="col-md-12">
                                                        <input placeholder="Date of birth" disabled class="form-control form-control-line">
                                                    </div>
                                                </div> --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
  </div>
</div>
@endsection

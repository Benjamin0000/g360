@extends('user.layout', ['title'=>' Indirect Downlines'])
@section('content')
<div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">Indirect Downline</h4>
                  <div class="table-responsive">
                     @include('user.downline.table')
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

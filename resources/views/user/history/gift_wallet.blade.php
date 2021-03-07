@extends('user.layout', ['title'=>' Gift-wallet Log'])
@section('content')
<div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">Gift-Wallet Transaction Log</h4>
                  <div class="table-responsive">
                     @include('user.history.table')
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

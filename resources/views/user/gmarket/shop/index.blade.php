@extends('user.layout', ['title'=>'My Store'])
@section('content')
  <div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Shops</h3>
    </div>
    <div class="card-body text-right">
        <a class="btn btn-primary" href="{{route('user.shop.create')}}"><i class="mdi mdi-plus-circle"></i> Create Shop</a>
    </div>
  </div>
@endsection

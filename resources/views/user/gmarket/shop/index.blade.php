@extends('user.layout', ['title'=>'My Store'])
@section('content')
@php
use App\Http\Helpers;
@endphp
<style>
  table th, td{text-align: center;}
</style>
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Shops</h3>
    </div>
    <div class="card-body text-right">
      <div style="margin-bottom:5px;">
       <a class="btn btn-primary" href="{{route('user.shop.create')}}"><i class="mdi mdi-plus-circle"></i> Create Shop</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Name</th>
              <th>Category</th>
              <th>Logo</th>
              {{-- <th>Location</th>
              <th>Address</th> --}}
              <th>Status</th>
              <th>Created</th>
            </tr>
          </thead>
          <tbody>
            @if($shops->count())
              @php $count = Helpers::tableNumber(10) @endphp
              @foreach($shops as $shop)
                <tr>
                  <td>{{$count++}}</td>
                  <td>{{$shop->name}}</td>
                  <td>{{$shop->category->name}}</td>
                  <td>
                    <img src="{{Storage::disk('do')->url($shop->logo)}}" width="50" class="img-fluid" alt="logo">
                  </td>
                  {{-- <td>
                    {{$shop->state->name}}
                    <div>{{$shop->city->name}}</div>
                  </td>
                  <td>{{$shop->address}}</td> --}}
                  <td>
                    @if($shop->status)
                      <span class="badge badge-success">Active</span>
                    @else
                      <span class="badge badge-danger">Down</span>
                    @endif
                  </td>
                  <td>
                    {{$shop->created_at->isoFormat('lll')}}
                    <div>{{$shop->created_at->diffForHumans()}}</div>
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
    </div>
  </div>
</div>
@endsection

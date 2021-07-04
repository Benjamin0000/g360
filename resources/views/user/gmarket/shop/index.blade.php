@extends('user.layout', ['title'=>'My Store'])
@section('content')
@php
use App\Http\Helpers;
@endphp
<style>
  th, td{text-align: center;}
</style>
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Shops</h3>
    </div>
    <div class="card-body">
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
              <th>Status</th>
              <th>Contact</th>
              <th>Created</th>
              <th>Action</th>
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
                  <td>
                    @if($shop->status)
                      <span class="badge badge-success">Active</span>
                    @else
                      <span class="badge badge-danger">Down</span>
                    @endif
                  </td>
                  <td>
                    <button data-toggle='modal' data-target='#details{{$shop->id}}' class="btn btn-primary btn-sm">Details</button>
                  </td>
                  <td>
                    {{$shop->created_at->isoFormat('lll')}}
                    <div>{{$shop->created_at->diffForHumans()}}</div>
                  </td>
                  <td>
                    <a href="{{route('user.shop.edit', $shop->id)}}" class="btn btn-info btn-sm">Edit</a>
                    <form action="{{route('user.shop.destroy', $shop->id)}}" method="post" style="display:inline;">
                       @csrf
                       @method('delete')
                       <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure about this?')">Delete</button>
                    </form>
                    <div style="padding-top:5px;">
                      <a href="{{route('user.product.index', $shop->id)}}" class="btn btn-primary btn-sm">Products</a>
                      <a href="{{route('user.shop.category', $shop->id)}}" class="btn btn-primary btn-sm">Categories</a>
                    </div>
                  </td>
                </tr>
                @include('user.gmarket.shop.contact_modal')
              @endforeach
            @endif
          </tbody>
        </table>
    </div>
  </div>
</div>
@endsection

@extends('user.layout', ['title'=>'Products'])
@section('content')
@php
use App\Http\Helpers;
@endphp
<style>
  th, td{text-align: center;}
</style>
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>[{{$shop->name}}] Products</h3>
    </div>
    <div class="card-body">
      <div style="margin-bottom:5px;">
       <a class="btn btn-primary" href="{{route('user.shop.index')}}"><i class="mdi mdi-arrow-left"></i>Back</a>
       <a class="btn btn-primary" href="{{route('user.product.create', $shop->id)}}"><i class="mdi mdi-plus-circle"></i> Add product</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Name</th>
              <th>Category</th>
              <th>Status</th>
              <th>Contact</th>
              <th>Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
    </div>
  </div>
</div>
@endsection

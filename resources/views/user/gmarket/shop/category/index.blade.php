@extends('user.layout', ['title'=>'Product Category'])
@section('content')
@php
use App\Http\Helpers;
@endphp
<style>
  th, td{text-align: center;}
</style>
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Product Categories [{{$shop->name}}]</h3>
    </div>
    <div class="card-body">
      <div style="margin-bottom:5px;">
       <a class="btn btn-primary" href="{{route('user.shop.index')}}"><i class="mdi mdi-angle-left"></i>Back</a>
      </div>
      <div class="row">
        <div class="col-md-6">
           <form action="" method="post">
             <br>
              <div class="form-group">
                <label for="">Category name</label>
                <input type="text" name="name" class="form-control" value="">
              </div>
              <div class="form-group">
                <button class="btn btn-primary">Create</button>
              </div>
           </form>
        </div>
        <div class="col-md-6">
          <br>
          <h5>Categories</h5>
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($categories->count())
                  @php $count = 1; @endphp
                  @foreach($categories as $category)
                    <tr>
                      <td>{{$count++}}</td>
                      <td>{{$category->name}}</td>
                      <td>
                         <button class="btn btn-sm btn-primary">Edit</button>
                         <form action="{{route('user.shop.saveCategory', $category->id)}}" method="post" style="display:inline;">
                           @csrf
                           @method('delete')
                           <button class="btn btn-sm btn-danger">Delete</button>
                         </form>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</div>
@endsection

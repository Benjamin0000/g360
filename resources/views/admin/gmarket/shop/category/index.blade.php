@extends('admin.layout', ['title'=>'SHOPS CATEGORY'])
@section('content')
@php
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <h3 class="float-left">Shop Categories</h3>
                    <div class="float-right">
                        <a href="{{route('admin.gmarket.shop')}}" class="btn btn-sm btn-info">Shops</a>
                    </div>
                </div>

                <div style="clear:right;clear:left;">
                <div class="row">
                    <div class="col-md-6">
                        {{-- <h4>Create Category</h4> --}}
                        <br>
                        <form method="POST" action="{{route('admin.gmarket.shop.category.create')}}">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" required name="name" class="form-control">
                            </div>
                            @csrf
                            <div class="form-group">
                                <button class="btn btn-info">Create</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <br>
                        <h4>All Categories</h4>
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
                                                <button data-toggle='modal' data-target='#edit{{$category->id}}' class="btn btn-info btn-sm">EDIT</button>
                                                <form method="post" action="{{route('admin.gmarket.shop.category.destroy', $category->id)}}" style="display: inline">
                                                    @csrf 
                                                    @method('delete')
                                                    <button onclick="return confirm('Have you thought about this?')" class="btn btn-danger btn-sm">DELETE</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @include('admin.gmarket.shop.category.edit_modal')
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div> 
@endsection

@extends('user.layout', ['title'=>'Add Product'])
@section('content')
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Add product [{{$shop->name}}]</h3>
    </div>
    <div class="card-body">
      <div class="" style="margin-bottom:5px;">
      <a class="btn btn-primary" href="{{route('user.product.index', $shop->id)}}"><i class="mdi mdi-arrow-left"></i> Back</a>
      </div>
      <br>
      <form action="" method="post">
          <div class="form-group">
            <label for="">Name</label>
            <input type="text" name="name" class="form-control" value="">
          </div>
          <div class="row">
            <div class="col-md-4">
                <label for="">Price</label>
                <input type="number" name="price" class="form-control" value="">
            </div>
            <div class="col-md-4">
                <label for="">Old Price</label>
                <input type="number" name="old_price" class="form-control" value="">
            </div>
            <div class="col-md-4">
                <label for="">Qty</label>
                <input type="number" name="qty" class="form-control" value="">
            </div>
          </div>
          <br>
          <div class="form-group">
            <label for="">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>

           <div class="form-group">
             <button class="btn btn-primary" name="button"><i class="mdi mdi-plus-circle"></i>Add product</button>
           </div>
      </form>
    </div>
</div>
@endsection

@extends('user.layout', ['title'=>'Create Store'])
@section('content')
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Create Store</h3>
    </div>
    <div class="card-body">
      <form class="" action="index.html" method="post">
         <div class="form-group">
           <label for="">Name</label>
           <input type="text" name="name" class="form-control" value="">
         </div>
         <div class="form-group">
           <label for="">State</label>
         </div>
         <div class="form-group">
           <label for="">Address</label>
           <input type="text" name="address" class="form-control" value="">
         </div>
         <div class="form-group">
            <div><label for="">Logo</label></div>
            <input type="file" name="" value="">
         </div>
         <div class="form-group">
            <label for="">Store Category</label>
            <select class="form-control" name="">
              <option value="">Beverage</option>
            </select>
         </div>
         <div class="form-group">
           <button class="btn btn-primary" name="button">Create Store</button>
         </div>
      </form>
    </div>
</div>
@endsection

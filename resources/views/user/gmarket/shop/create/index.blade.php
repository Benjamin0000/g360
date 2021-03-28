@extends('user.layout', ['title'=>'Create Shop'])
@section('content')
@php
$states = config('states');
@endphp
<style type="text/css">
.lds-ripple{display:inline-block;position:relative;width:80px;height:80px}.lds-ripple div{position:absolute;border:4px solid #bb5de0;opacity:1;border-radius:50%;animation:lds-ripple 1s cubic-bezier(0,.2,.8,1) infinite}.lds-ripple div:nth-child(2){animation-delay:-.5s}@keyframes lds-ripple{0%{top:36px;left:36px;width:0;height:0;opacity:1}100%{top:0;left:0;width:72px;height:72px;opacity:0}}
</style>
<div class="card" style="min-height:80vh;">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Create A Shop</h3>
    </div>
    <div class="card-body">
      <form enctype="multipart/form-data" action="{{route('user.shop.save')}}" method="post">
           <div class="form-group">
             <input type="text" placeholder="Name Of Shop" name="name" class="form-control" value="">
           </div>
           <div class="form-group">
              <div><label for="">Logo &nbsp;<small class="text-danger">(100kb max)</small></label></div>
              <input type="file" name="logo" value="">
           </div>
           <div class="form-group">
              <select class="form-control" name="category">
                <option value="">Select Category</option>
                @if($categories->count())
                  @foreach($categories as $category)
                    <option value="{{$category->name}}">{{$category->name}}</option>
                  @endforeach
                @endif
              </select>
           </div>
           @csrf
           <div class="form-group">
             <select name="state" class="form-control" required id="state">
                <option value="">Select State</option>
                @foreach($states as $state)
                  <option value="{{$state['name']}}">{{$state['name']}}</option>
                @endforeach
             </select>
           </div>
           <div id="city_s_c"></div>
           <div class="form-group">
                <textarea name="address" class="form-control" placeholder="Enter address"></textarea>
           </div>
           <div class="form-group">
             <button class="btn btn-primary" name="button"><i class="mdi mdi-plus-circle"></i> Create Store</button>
           </div>
      </form>
    </div>
</div>
<script type="text/javascript">
onReady(function(){
  $('#state').on('change', function(){
    var loader = "<div class='text-center'><div class='lds-ripple'><div></div><div></div></div></div>";
    var msg = "<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Sorry registration not possible at the moment</div>";
    $('#city_s_c').html(loader);
      $.ajax({
        type:'get',
        url:'{{route('user.shop.getCities')}}?s='+this.value+'&t=c',
        success:function(data){
          if(data.data){
            $('#city_s_c').html(data.data);
          }else{
            $('#city_s_c').html(msg);
          }
        },
        error:function(){
          $('#city_s_c').html(msg);
        }
      });
  });
});
</script>
@endsection

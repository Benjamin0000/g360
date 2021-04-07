@extends('user.layout', ['title'=>'Agent Application'])
@php
$user = Auth::user();
$states = config('states');
@endphp
@section('content')
<div class="card" style="margin:0;">
  <div class="card-header text-center" style="background:#eee;">
    <h3>Agent's Application</h3>
  </div>
  <div class="card-body">
    <form action="{{route('user.agent.create')}}" method="post">
      <div class="form-group">
        <select name="state" class="form-control" required id="state">
           <option value="">Select State</option>
           @foreach($states as $state)
             <option value="{{$state['name']}}">{{$state['name']}}</option>
           @endforeach
        </select>
      </div>
      <div id="city_s_c"></div>
      @csrf
      <div class="form-group">
        <button class="btn btn-primary">Submit</button>
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

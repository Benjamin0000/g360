<div class="form-group">
<select name="city" required class="form-control" id="city_s">
    <option value="">Select City</option>
  @foreach($cities as $city)
  	<option value="{{$city}}">{{$city}}</option>
  @endforeach
</select>
</div>
<div id="p_s_c"></div>
<script type="text/javascript">
$('#city_s').on('change', function(){
var loader = "<div class='text-center'><div class='lds-ripple'><div></div><div></div></div></div>";
var msg = "<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Sorry registration not possible at the moment</div>";
    $('#p_s_c').html(loader);
      $.ajax({
        type:'get',
        url:'{{route('user.shop.getCities')}}?s='+this.value+'&t=p',
        success:function(data){
          if(data.data){
            $('#p_s_c').html(data.data);
          }else{
            $('#p_s_c').html('');
          }
        },
        error:function(){
          $('#p_s_c').html(msg);
        }
      });
 });
</script>

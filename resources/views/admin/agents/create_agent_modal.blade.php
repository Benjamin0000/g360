<div class="modal"  id="create">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">CREATE NEW AGENT</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('admin.agents.create')}}" method="post">
            <div class="form-group">
                <label for="">G-number</label>
                <input type="text" name="gnumber" value="" class="form-control">
            </div>
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
                 <button class="btn btn-primary">CREATE</button>
            </div>
          </form>
        </div>
      </div>
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

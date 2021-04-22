<div class="modal"  id="def{{$gtr->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><b>Level {{$gtr->id}}</b> Default User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="def_gtr" action="{{route('admin.gsteam.addDefault', $gtr->id)}}" method="post">
              <div class="row" id="info_f">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Gnumber</label>
                      <input type="text" required name="gnumber" value="" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Total Referrals</label>
                      <input type="text" required name="referrals" value="" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Tag</label>
                      <input type="text" required name="tag" value="" class="form-control">
                    </div>
                  </div>
              </div>
              <div id="show"></div>
              <input type="hidden" name="con" value="0" id="con">
              @method('put')
              @csrf
              <div class="form-group text-center">
                  <button id="add_btn" class="btn btn-primary">Search</button> 
                  <button type="button" id="cancel_def" class="btn btn-danger">Cancel</button>
              </div>
              <div id="aerror"></div>
            </form>
          </div>
        </div>
    </div>
  </div>
<script>
onReady(function(){
  $("#cancel_def").hide();
  $('#def_gtr').on('submit', function(e){
    e.preventDefault();
    var loader ="<div class='spinner-border' role='status'><span class='sr-only'>Loading...</span></div>";
    var $ele = $('#add_btn');
    $ele.data('text',$ele.text());
    $ele.html(loader);
    $ele.prop('disabled',true);
    $.ajax({
      url:'{{route('admin.gsteam.addDefault', $gtr->id)}}',
      type:'POST',
      data:$(this).serialize(),
      success:function(data){
        if(data.info){
          $("#show").html(data.info);
          $("#con").val(1);
          $ele.text('Continue');
          $ele.prop('disabled',false);
          $("#info_f").hide();
          $("#cancel_def").show();
        }else if(data.success){
          $ele.text($ele.data('text'));
          $("#aerror").html("<div class='alert alert-success'>Default User added</div>");
          window.location.reload();
        }else if(data.error){
          $("#aerror").html("<div class='alert alert-error'> <i class='fa fa-info-circle'></i> "+data.error+"</div>");
          $ele.text($ele.data('text'));
          $ele.prop('disabled',false);
        }
      },
      error:function(){
        $("#aerror").html("<div class='alert alert-danger'>Something went wrong</div>");
        $ele.text($ele.data('text'));
        $ele.prop('disabled',false);
      }
    });
  });
  $("#cancel_def").on('click', function(e){
    var $ele = $('#add_btn');
    $ele.html('Search');
    $("#info_f").show();
    $("#con").val(0);
    $("#show").html('');
    $(this).hide();
  });
});
</script>
  
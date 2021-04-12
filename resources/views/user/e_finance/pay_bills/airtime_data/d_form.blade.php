<form id="data_form" action="" method="post">
    <div class="form-group">
       <input type="text" id="dnum" class="form-control fc" name="mobile_number" value="" placeholder="Mobile Number">
    </div>
    <div class="form-group">
      <div style="font-size:15px;">Select network provider</div>
      <div class="n-img-c">
        @foreach($datasub as $data)
          <img src="{{asset($data->logo)}}" alt="" class="n-img d-img" dval='{{$data->name}}'>
        @endforeach
      </div>
    </div>
    @csrf
    <input type="hidden" name="operator" id="oprtd" value="">
    <div id="plan"></div>
    <div id="d_error"></div>
    <div class="form-group">
      <button id="bdbtn" class="btn btn-primary btn-block">Buy</button>
    </div>
</form>
<script type="text/javascript">
  onReady(function(){
    var loader = "<div class='text-center'><div class='lds-ripple'><div></div><div></div></div></div>";
      $(".d-img").on('click', function(e){
          $('#oprtd').val($(this).attr('dval'));
          $("#plan").html(loader);
          $('#d_error').html('');
          $.ajax({
            url:"{{route('user.pay_bills.getdata_plan')}}",
            type:'post',
            data:$('#data_form').serialize(),
            success:function(data){
              $('#plan').html(data);
            },
            error:function(){
              $('#plan').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i>Not available</div>");
            }
          });
      });
      $("#data_form").on('submit', function(e){
          e.preventDefault();
          var $ele = $('#bdbtn');
          $ele.data('text', $ele.text());
          $ele.html(get_loader());
          $ele.prop('disabled',true);
          $('#d_error').html('');
          $.ajax({
            url:"{{route('user.pay_bills.purchaseData')}}",
            type:'post',
            data:$('#data_form').serialize(),
            success:function(data){
               if(data.status){
                 $('#dnum').val('');
                 $('#plan').html('');
                 swal({
                   title: "Transaction Successful",
                   text: "",
                   icon: "success",
                   button: "Ok",
                 });
               }else{
                 $('#d_error').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> "+data.error+"</div>");
               }
               $ele.text($ele.data('text'));
               $ele.prop('disabled',false);
            },
            error:function(){
              $('#d_error').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Request could not be processed</div>");
              $ele.text($ele.data('text'));
              $ele.prop('disabled',false);
            }
          });
      });
  });
</script>

<form id="aform" method="post">
    <div class="form-group">
       <input type="text" id="anum" required class="form-control fc" name="mobile_number" value="" placeholder="Mobile Number">
    </div>
    <div class="form-group">
      <div style="font-size:15px;">Select network provider</div>
      <div class="n-img-c">
        @foreach($airtimes as $airtime)
          <img src="{{asset($airtime->logo)}}" alt="" class="n-img" onclick="document.getElementById('oprt').value='{{$airtime->name}}'">
        @endforeach
      </div>
    </div>
   <input type="hidden" name="operator" value="" id="oprt">
    <div class="input-group mb-3">
      <div class="input-group-prepend">
          <span class="input-group-text cur" style="background:#fff !important;">{{$cur}}</span>
      </div>
      <input type="number" id="aamt" required placeholder="Amount" class="form-control fc" name="amount" value="">
    </div> @csrf
    <div id="a_error"></div>
    <div class="form-group">
      <button class="btn btn-primary btn-block" id="babtn">Buy</button>
    </div>
</form>
<script type="text/javascript">
onReady(function(){
  $('#aform').on('submit', function(e){
      e.preventDefault();
      var $ele = $('#babtn');
      $ele.data('text',$ele.text());
      $ele.html(get_loader());
      $ele.prop('disabled',true);
      $('#a_error').html("");
      $.ajax({
        url:'{{route('user.pay_bills.airtime')}}',
        type:'post',
        data:$(this).serialize(),
        success:function(data){
          if(data.status){
            $('#anum').val('');
            $('#aamt').val('');
            swal({
              title: "Transaction Successful",
              text: "",
              icon: "success",
              button: "Ok",
            });
          }else{
            $('#a_error').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> "+data.error+"</div>");
          }
          $ele.text($ele.data('text'));
          $ele.prop('disabled',false);
        },
        error:function(){
          $('#a_error').html("<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Request could not be processed</div>");
          $ele.text($ele.data('text'));
          $ele.prop('disabled',false);
        }
      });
  });
});
</script>

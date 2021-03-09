<script type="text/javascript">
  onReady(function(){
      var cur = '{{$cur}}';
      var xhr_error = "<div class='alert alert-danger'><i class='fa fa-info-circle'></i> Transaction could not be completed</div>";
      $('#wtf').on('submit', function(e){
        $('#intTe').html('');
        var $ele = $('#itb');
        $ele.data('text',$ele.text());
        $ele.html(get_loader());
        $ele.prop('disabled',true);
        e.preventDefault();
        $.ajax({
          type:'POST',
          url:'{{route('user.gfund.withdrawalTransfer')}}',
          data:$(this).serialize(),
          success:function(data){
            if(data.status){
                $('.bal').html(data.bal);
                $('#bal3').html(data.bal3);
                $('#intTe').html("<div class='alert alert-success'><i class='fa fa-check-circle'></i> "+data.msg+"</div>");
                $('.amt').val('');
            }else{
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                $('#intTe').html("<div class='alert alert-danger'> <i class='fa fa-info-circle'></i> "+data.msg+"</div>");
            }
            $ele.text($ele.data('text'));
            $ele.prop('disabled',false);
          },
          error: function(xhr, status, error) {
              $ele.text($ele.data('text'));
              $ele.prop('disabled',false);
              $('#intTe').html(xhr_error);
          }
        });
      });
      $("#trf1_others").on('submit', function(e){
          e.preventDefault();
          $('#trf1_othersE').html('');
          var $ele = $('#trf1b');
          $ele.data('text',$ele.text());
          $ele.html(get_loader());
          $ele.prop('disabled',true);
          $.ajax({
              url:'{{route('user.gfund.getMemeberDetail')}}',
              type:'POST',
              data:$(this).serialize(),
              success:function(data){
                if(data.name){
                  $('#trf1_others').slideUp();
                  $('#sec_f_to_sh').show();
                  $('#aaa').html(cur+data.amount.toLocaleString());
                  $('#rrr').html(data.name);
                  $('#ggg').html(data.gnumber);
                  $('#uuu').html(data.username);
                  $('#s_amt').val(data.amount);
                  $('#s_gnum').val(data.gnumber);
                }else{
                    $ele.text($ele.data('text'));
                    $ele.prop('disabled',false);
                    $('#trf1_othersE').html("<div class='alert alert-danger'> <i class='fa fa-info-circle'></i> "+data.msg+"</div>");
                }
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
              },
              error: function(xhr, status, error) {
                  $ele.text($ele.data('text'));
                  $ele.prop('disabled',false);
                  $('#trf1_othersE').html(xhr_error);
              }
          });
      });
      $('#c_t_oth').on('submit', function(e){
          e.preventDefault();
          $('#c_t_othE').html('');
          var $ele = $('#ctbtn');
          $ele.data('text',$ele.text());
          $ele.html(get_loader());
          $ele.prop('disabled',true);
          $.ajax({
              url:'{{route('user.gfund.transMembers')}}',
              type:'POST',
              data:$(this).serialize(),
              success:function(data){
                if(data.status){
                  $('.bal').html(data.bal.toLocaleString());
                  $('#trf1_others').show();
                  $('#sec_f_to_sh').hide();
                  $('.amt').val('');
                  $('#rgnum').val('');
                  swal({
                    title: "Transfer successful",
                    text: cur+data.amount.toLocaleString()+' sent to '+data.receiver,
                    icon: "success",
                    button: "Ok",
                  });
                }else{
                    $ele.text($ele.data('text'));
                    $ele.prop('disabled',false);
                    $('#c_t_othE').html("<div class='alert alert-danger'> <i class='fa fa-info-circle'></i> "+data.msg+"</div>");
                }
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
              },
              error: function(xhr, status, error) {
                  $ele.text($ele.data('text'));
                  $ele.prop('disabled',false);
                  $('#c_t_othE').html(xhr_error);
              }
          });
      });
      $('#cancel_int_t').on('click', function(){
          $('#trf1_others').show();
          $('#sec_f_to_sh').hide();
      });
      $('#wtt').on('submit', function(e){
        $('#intTte').html('');
        var $ele = $('#ittb');
        $ele.data('text',$ele.text());
        $ele.html(get_loader());
        $ele.prop('disabled',true);
        e.preventDefault();
        $.ajax({
          type:'POST',
          url:'{{route('user.gfund.trxWalletTransfer')}}',
          data:$(this).serialize(),
          success:function(data){
            if(data.status){
                $('#bal3').html(data.bal);
                $('.bal').html(data.bal2);
                $('#intTte').html("<div class='alert alert-success'><i class='fa fa-check-circle'></i> "+data.msg+"</div>");
                $('.amt').val('');
            }else{
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                $('#intTte').html("<div class='alert alert-danger'> <i class='fa fa-info-circle'></i> "+data.msg+"</div>");
            }
            $ele.text($ele.data('text'));
            $ele.prop('disabled',false);
          },
          error: function(xhr, status, error) {
              $ele.text($ele.data('text'));
              $ele.prop('disabled',false);
              $('#intTte').html(xhr_error);
          }
        });
      });
  });
</script>

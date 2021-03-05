@extends('user.layout', ['title'=>'Gfund'])
@section('content')
@php
  $cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <br/>
                <div class="card-body">
                    <h4 class="card-title">Transfer Funds</h4>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#b" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Local Bank</span></a> </li>
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#o" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Other Members</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#w" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-wallet"></i></span> <span class="hidden-xs-down">Wallet</span></a> </li>
                    </ul>
                    <!-- Tab panes -->
              <div class="tab-content tabcontent-border">
                <div class="tab-pane active p-3 pt-5" id="b" role="tabpanel">
                    <form class="form-horizontal" method="post" action="">
                        <div class="form-group">
                            <label>Recipient Account-Number </label>
                            <input type="text" class="form-control" name="pin" />
                        </div>
                        <div class="form-group">
                            <label>Current Password </label>
                            <input type="text" class="form-control" name="pin" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary ml-2">Request Transfer </button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane p-3 pt-5" id="o" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">Recipient G-Number  </h4>
                            <form class="form-inline" method="post" action="">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="ident" />
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary ml-2">Continue</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-3 pt-5" id="w" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                      <div class="text-center">
                         <h2>{{$cur}}<span id="bal">{{Auth::user()->w_balance}}</span></h2>
                      </div>
                         <form id='wtf'  method="post">
                           <div class="form-group">
                             <input required type="text" id="amt" name="amount" placeholder="amount" class="form-control" value="">
                           </div>
                           <div class="form-group">
                              <label for="">Select wallet</label>
                              <select class="wide sselect form-control" name="wallet">
                                  <option value=""></option>
                                  <option value="tw">T-Wallet</option>
                                  <option value="pkg">PKG-Wallet</option>
                              </select>
                           </div>@csrf
                           <div class="form-group">
                             <br><br>
                              <div id="intTe"></div>
                              <button id="itb" class="btn btn-primary btn-block">Continue</button>
                           </div>
                         </form>
                       </div>
                     </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  onReady(function(){
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
          url:'{{route('user.gfund.wtranfer')}}',
          data:$(this).serialize(),
          success:function(data){
            if(data.status){
                $('#bal').html(data.bal);
                $('#intTe').html("<div class='alert alert-success'><i class='fa fa-check-circle'></i> "+data.msg+"</div>");
                $('#amt').val('');
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
  });
</script>
@endsection

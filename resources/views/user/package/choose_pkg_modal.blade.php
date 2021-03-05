<div class="modal" tabindex="-1" id="choosem{{$package->id}}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ucfirst($package->name)}} Package</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <h4 class="text-center">Select a payment method</h4>
            <form id="pkgf{{$package->id}}">
                <div class="form-group" style="">
                    <select class="wide pay_select" required name="pay_method" tel='{{$package->id}}'>
                        <option value="">Choose method</option>
                        <option value="e-pin">E-pin</option>
                        <option value="trx_w">Transaction Wallet</option>
                    </select>
                </div>
                <div class="epin_show" id="epin_show{{$package->id}}">
                  <br>
                  <br>
                  <br>
                  <div class="form-group">
                      <input type="text" name="epin" required class="form-control" placeholder="Enter E-pin">
                  </div>
                </div>
                <div id="space_epin{{$package->id}}"><br><br></div>
                <div class="form-group text-center">
                    <div><label for="">Sign up for Health Insurance?</label></div>
                    <div class="form-control">
                        <label>Yes <input value="yes" checked type="radio" name="h" ></label> &nbsp; &nbsp;
                        <label>No <input  value='no' type="radio" name="h" ></label>
                    </div>
                </div>
                <input type="hidden" name='p' value="{{rand(10,20).$package->id}}">
                @csrf
                <div class="text-center" id="pkge{{$package->id}}"></div>
                <div class="form-group text-center">
                    <button class="btn btn-success" id='pkgcbtn{{$package->id}}'>Continue</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<script>
  onReady(function() {
    $('#pkgf{{$package->id}}').on('submit', function(e){
        e.preventDefault();
        selectP({{$package->id}});
    });
  });
</script>

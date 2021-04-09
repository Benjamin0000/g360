<div class="modal"  id="edit{{$gtr->id}}">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">EDIT GSTEAM LEVEL</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('admin.gsteam.update', $gtr->id)}}" method="post">
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Amount</label>
                    <input type="text" name="amount" value="{{(int)$gtr->amount}}" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Payback</label>
                    <input type="text" name="pay_back" value="{{(int)$gtr->pay_back}}" class="form-control">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Total Givers</label>
                    <input type="text" name="r_count" value="{{$gtr->r_count}}" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Givers Hours</label>
                    <input type="text" name="g_hours" value="{{$gtr->g_hours}}" class="form-control">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Receivers Days</label>
                    <input type="text" name="r_days" class="form-control" value="{{$gtr->r_days}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Total Referrals</label>
                    <input type="text" name="total_ref" class="form-control" value="{{$gtr->total_ref}}">
                  </div>
                </div>
            </div>
            @method('put')
            @csrf
            <div class="form-group">
                 <button class="btn btn-primary">UPDATE</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

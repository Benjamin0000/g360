<div class="modal"  id="edit{{$package->id}}">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">EDIT {{ucwords($package->name)}} Package</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('admin.package.update', $package->id)}}" method="post">
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{$package->name}}" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Amount</label>
                    <input type="text" name="amount" @if($package->id == 1) readonly @endif  value="{{(int)$package->amount}}" class="form-control">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">PV</label>
                    <input type="text" name="pv" value="{{$package->pv}}" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">H-Token</label>
                    <input type="text" name="h_token" value="{{$package->h_token}}" class="form-control">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Ref. PV</label>
                    <input type="text" name="ref_pv" class="form-control" value="{{$package->ref_pv}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Ref. H-Token</label>
                    <input type="text" name="ref_h_token" class="form-control" value="{{$package->ref_h_token}}">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Ref. Com. Percent</label>
                    <input type="text" name="ref_percent" class="form-control" value="{{$package->ref_percent}}">
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                       <label for="">Insurance</label>
                       <input type="text" name="insurance" value="{{$package->insurance}}" class="form-control">
                    </div>
                </div>
            </div>
              <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Ref. level Gen.</label>
                      <input type="text" name="gen" class="form-control" value="{{$package->gen}}">
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

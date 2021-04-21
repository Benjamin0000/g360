<div class="modal"  id="def{{$gtr->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><b>Level {{$gtr->id}}</b> Default Users</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('admin.gsteam.addDefault', $gtr->id)}}" method="post">
              <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Gnumber</label>
                      <input type="text" name="gnumber" value="" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Total Referrals</label>
                      <input type="text" name="referrals" value="" class="form-control">
                    </div>
                  </div>
              </div>
              @method('put')
              @csrf
              <div class="form-group">
                   <button class="btn btn-primary">ADD</button>
              </div>
            </form>
          </div>
        </div>
    </div>
  </div>
  
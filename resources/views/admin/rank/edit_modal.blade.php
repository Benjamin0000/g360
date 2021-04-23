<div class="modal"  id="edit{{$rank->id}}">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">EDIT RANK {{ucwords($rank->name)}}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('admin.rank.update', $rank->id)}}" method="post">
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{$rank->name}}" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Prize</label>
                    <input type="text" name="prize" value="{{(int)$rank->prize}}" class="form-control">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Loan</label>
                    <input type="text" name="loan" value="{{(int)$rank->loan}}" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">PV</label>
                    <input type="text" name="pv" value="{{$rank->pv}}" class="form-control">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Loan exp. months</label>
                    <input type="text" name="loan_exp_months" class="form-control" value="{{$rank->loan_exp_m}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">LMP</label>
                    <input type="text" name="lmp" class="form-control" value="{{(int)$rank->total_lmp}}">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">LMP exp. Months</label>
                    <input type="text" name="lmp_exp_months" class="form-control" value="{{$rank->lmp_months}}">
                  </div>
                </div>
                <div class="col-md-6">
                  @if($rank->id == 1)
                    <div class="form-group">
                       <label for="">Fee</label>
                       <input type="text" name="fee" value="{{(int)$rank->fee}}" class="form-control">
                    </div>
                  @else 
                    <div class="form-group">
                      <label for="">Carry over</label>
                      <input type="text" name="carry_over" value="{{(int)$rank->carry_over}}" class="form-control">
                    </div>
                  @endif
                </div>
            </div>
            @if($rank->id == 1)
              <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Minutes</label>
                      <input type="text" name="minutes" class="form-control" value="{{$rank->minutes}}">
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                         <label for="">Grace Minutes</label>
                         <input type="text" name="graced_minutes" value="{{$rank->graced_minutes}}" class="form-control">
                      </div>
                  </div>
              </div>
              <div class="form-group">
                <label for="">Carry over</label>
                <input type="text" name="carry_over" value="{{(int)$rank->carry_over}}" class="form-control">
              </div>
            @endif
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

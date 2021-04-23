<div class="modal"  id="edit{{$loanPlan->id}}">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">ADD LOAN</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('admin.finance.updateLoanSettings', $loanPlan->id)}}" method="post">
            <div class="form-group">
               <label for="name">Name</label>
               <input type="text" name="name" value="{{$loanPlan->name}}" class="form-control">
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Min</label>
                    <input type="text" name="min" class="form-control" value="{{(int)$loanPlan->min}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Max</label>
                    <input type="text" name="max" class="form-control" value="{{(int)$loanPlan->max}}">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Interest</label>
                    <input type="text" name="interest" class="form-control" value="{{$loanPlan->interest}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Grace Interest</label>
                    <input type="text" name="grace_interest" class="form-control" value="{{$loanPlan->f_interest}}">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Expiry Months</label>
                    <input type="text" name="expiry_months" class="form-control" value="{{$loanPlan->exp_months}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Grace Months</label>
                    <input type="text" name="grace_months" class="form-control" value="{{$loanPlan->grace_months}}">
                  </div>
                </div>
            </div>
            @csrf
            <div class="form-group">
                 <button class="btn btn-primary">UPDATE</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

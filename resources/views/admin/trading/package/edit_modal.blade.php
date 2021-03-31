<div class="modal"  id="edit{{$package->id}}">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">{{ucfirst($package->name)}} Plan</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form  action="{{route('admin.trading.updatePackage', $package->id)}}" method="post">
        <div class="form-group">
            <label for="">Amount</label>
            <input type="number" name="amount" value="{{$package->amount}}" class="form-control">
        </div>
        <div class="form-group">
          <label for="">Interest in %</label>
          <input type="text" name="interest" value="{{$package->interest}}" class="form-control">
        </div>
         <div class="form-group">
             <label for="">Name</label>
             <input type="text" name="name" value="{{$package->name}}" class="form-control">
         </div>
         @method('put')
         @csrf
         <div class="form-group">
             <label for="">PV</label>
             <input type="number" name="pv" value="{{$package->pv}}" class="form-control">
         </div>
         <div class="form-group">
             <label for="">Referral PV</label>
             <input type="number" name="referral_pv" value="{{$package->ref_pv}}" class="form-control">
         </div>
         <div class="form-group">
             <label for="">Expiry Days</label>
             <input type="number" name="expiry_days" value="{{$package->exp_days}}" class="form-control">
         </div>
         <div class="form-group">
           <label for="">Referral commission</label>
           <input type="text" name="referral_commission" value="{{$package->ref_percent}}" class="form-control" placeholder="Eg: 1.3, 4.6, 5.3, 4.5">
         </div>
         <div class="form-group text-right">
             <button class="btn btn-primary">Update Package</button>
         </div>
      </form>
    </div>
  </div>
  </div>
</div>

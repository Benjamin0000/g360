<div class="modal"  id="airtime{{$airtime->id}}">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">{{ucfirst($airtime->name)}}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form  action="{{route('admin.finance.vtu.updateAirtime', $airtime->id)}}" method="post">
        <div class="form-group">
            <label for="">Min Buy</label>
            <input type="number" name="min_buy" value="{{$airtime->min_buy}}" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Max Buy</label>
            <input type="number" name="max_buy" value="{{$airtime->max_buy}}" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Comm. %</label>
            <input type="text" name="commission" value="{{$airtime->comm}}" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Ref. Amount	</label>
            <input type="text" name="referral_amount" value="{{$airtime->ref_amt}}" class="form-control">
        </div>
         @method('put')
         @csrf
         <div class="form-group text-right">
             <button class="btn btn-primary">Update</button>
         </div>
      </form>
    </div>
  </div>
  </div>
</div>

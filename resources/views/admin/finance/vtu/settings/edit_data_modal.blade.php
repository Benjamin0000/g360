<div class="modal"  id="data{{$datasub->id}}">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">{{ucfirst($datasub->name)}}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form  action="{{route('admin.finance.vtu.updateData', $datasub->id)}}" method="post">
        <div class="form-group">
            <label for="">Comm. %</label>
            <input type="number" name="commission" value="{{$datasub->comm}}" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Ref. Amount	</label>
            <input type="text" name="referral_amount" value="{{$datasub->ref_amt}}" class="form-control">
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

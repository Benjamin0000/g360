<div class="modal"  id="edit{{$cable->id}}">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">{{ucfirst($cable->name)}}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form  action="{{route('admin.finance.cableTv.update', $cable->id)}}" method="post">
        <div class="form-group">
            <label for="">name</label>
            <input type="text" name="name" value="{{$cable->name}}" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Charge</label>
            <input type="text" name="charge" value="{{$cable->charge}}" class="form-control">
        </div>
        <div class="form-group">
          <label for="">Comm. amt</label>
          <input type="text" name="comm_amt" value="{{$cable->comm_amt}}" class="form-control">
        </div>
        <div class="form-group">
          <label for="">Ref. amt</label>
          <input type="text" name="ref_amt" value="{{$cable->ref_amt}}" class="form-control">
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

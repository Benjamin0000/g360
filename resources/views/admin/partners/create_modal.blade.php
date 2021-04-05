<div class="modal"  id="create">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">CREATE NEW PARTNER</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('admin.partner.store')}}" method="post">
            <div class="form-group">
                <label for="">G-number</label>
                <input type="text" name="gnumber" value="" class="form-control">
            </div>
            <div class="form-group">
              <label for="">Amount</label>
              <input type="number" name="amount" value="" class="form-control">
            </div>
            <div class="form-group">
              <label for="">Total Return</label>
              <input type="number" name="total_return" value="" class="form-control">
            </div>
            <div class="form-group">
               <label for="">Type</label>
               <select class="form-control" name="type">
                  <option value="">Select</option>
                  <option value="1">Super</option>
                  <option value="0">Sub</option>
               </select>
            </div>
            <div class="form-group">
                <label for="">Duration (In Months)</label>
                <input type="text" name="duration" value="" class="form-control">
            </div>
            @csrf
            <div class="form-group">
                 <button class="btn btn-primary">CREATE</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

<div class="modal"  id="cashout">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">CASHOUT</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('user.partnership.cashout')}}" method="post">
            <div class="form-group">
              {{-- <div class="text-center"><label for="">Amount</label></div> --}}
              <input type="text" name="amount" value="" class="form-control" placeholder="Enter amount">
            </div>
            @csrf
            <div class="form-group">
                 <button class="btn btn-primary btn-block">SUBMIT</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

<div class="modal"  id="edit{{$partner->id}}">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">EDIT PARTNER</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('admin.partner.update', $partner->id)}}" method="post">
            <div class="form-group">
              <label for="">Signup credits</label>
              <input type="text" name="signup_credits" value="{{$partner->s_credit}}" class="form-control">
            </div>
            <div class="form-group">
              <label for="">Finance credits</label>
              <input type="text" name="finance_credits" value="{{$partner->f_credit}}" class="form-control">
            </div>
            <div class="form-group">
              <label for="">Eshop credits</label>
              <input type="text" name="eshop_credits" value="{{$partner->e_credit}}" class="form-control">
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

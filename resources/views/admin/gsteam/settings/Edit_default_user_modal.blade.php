<div class="modal"  id="editdefaultuser{{$gsclub->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><b>Level {{$gtr->id}}</b> Default</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="def_gtr" action="{{route('admin.gsteam.UpdateDefaultUser', [$gtr->id, $gsclub->id])}}" method="post">
              <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Total Referrals</label>
                      <input type="text" required name="referrals" value="{{$gsclub->def_refs}}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Tag</label>
                      <input type="text" required name="tag" value="{{$gsclub->tag}}" class="form-control">
                    </div>
                  </div>
              </div>
              @method('put')
              @csrf
              <div class="form-group text-center">
                  <button class="btn btn-primary">UPDATE</button> 
                  <button class="btn btn-danger rmdef" type="button" data-f='deldefUser{{$gsclub->id}}'>Remove</button>
              </div>
            </form>
            <form id="deldefUser{{$gsclub->id}}" action="{{route('admin.gsteam.UpdateDefaultUser', [$gtr->id, $gsclub->id])}}" method="post" method="POST">
                @method('put')
                @csrf
                <input type="hidden" name="del" value="1">
            </form>
          </div>
        </div>
    </div>
  </div>


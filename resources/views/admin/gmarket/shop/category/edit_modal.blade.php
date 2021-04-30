<div class="modal"  id="edit{{$category->id}}">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">EDIT CATEGORY</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form  action="{{route('admin.gmarket.shop.category.update', $category->id)}}" method="post">
           <div class="form-group">
              <label>Name</label>
              <input required type="text" name="name" placeholder="category name" value="{{$category->name}}" class="form-control">
           </div>
           @method('put')
           @csrf
           <button class="btn btn-success btn-block">UPDATE</button>
        </form>
        </div>
      </div>
  </div>
</div>
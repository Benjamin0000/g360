<div class="modal"  id="details{{$shop->id}}">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">SHOP CONTACT</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <h4>Phone</h4>
           <div>{{$shop->phone_number}}</div>
           <h4>Address</h4>
           <div>{{$shop->address}}</div>
        </div>
      </div>
  </div>
</div>

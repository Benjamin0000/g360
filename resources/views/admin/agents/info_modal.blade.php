<div class="modal"  id="info{{$agent->id}}">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">AGENT INFO</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <table class="table">
              <tr>
                <th>Name</th>
                <td>{{ucwords($agent->user->fname.' '.$agent->user->lname)}}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{$agent->user->email}}</td>
              </tr>
              <tr>
                <th>Mobile</th>
                <td>{{$agent->user->phone}}</td>
              </tr>
              <tr>
                <th>Location</th>
                <td>{{$agent->user->state_id}}, {{$agent->user->city_id}}</td>
              </tr>
              <tr>
                <th>Package</th>
                <td>{{ucwords($agent->user->package->name)}}</td>
              </tr>
              <tr>
                <th>Joined</th>
                <td>{{$agent->user->created_at->diffForHumans()}}</td>
              </tr>
           </table>
        </div>
      </div>
  </div>
</div>

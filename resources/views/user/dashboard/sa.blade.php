@php
use Carbon\Carbon;
$sup_as_rank = App\Models\Rank::find(1);
$sa_fee = $sup_as_rank->fee;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@if($associate = $user->superAssoc)
  @if($associate->status != 3 && $associate->status != 4)
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Super Associate</h4>
                <div class="text-center">
                @php $status = $associate->status @endphp
                @if($status == 1)
                  <form  action="{{route('user.dashboard.rassoc')}}" method="post" onsubmit="return confirm('{{$cur}}{{number_format($sa_fee)}} will be charged as reactivation fee.')">
                    @csrf
                    <input type="hidden" name="type" value="ac">
                    <button class="btn btn-primary btn-sm">Reactivate</button>
                  </form>
                @elseif($status == 2)
                      <button data-toggle='modal' data-target='#assoc' class="btn btn-success btn-sm blink"><i class="mdi mdi-trophy-award"></i> Claim now</button>
                      <div class="modal" tabindex="-1" id="assoc">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Super Associate Reward</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                               <form action="{{route('user.dashboard.rassoc')}}" method="post">
                                 <div class="form-group">
                                   <label for="">Select a reward type</label>
                                   <select class="form-control" name="tp">
                                      <option value="">Choose</option>
                                      @php $lmp = $sup_as_rank->total_lmp/$sup_as_rank->lmp_months @endphp
                                      <option value="m">{{$cur}}{{number_format($lmp)}} monthly payment</option>
                                      <option value="l">{{$cur}}{{number_format($sup_as_rank->loan)}} loan</option>
                                   </select>
                                 </div>
                                 @csrf
                                 <input type="hidden" name="type" value="cl">
                                 <div class="form-group">
                                    <button class="btn btn-success">Continue</button>
                                 </div>
                               </form>
                            </div>
                          </div>
                        </div>
                      </div>
                  @elseif($associate->last_grace != '')
                      <h2 style="font-size:18px;" class="font-light mb-0">
                        <b><span id="clock"></span></b>
                      </h2>
                      @if($associate->balance_leg)
                        <div class="text-danger blink"><b>Balance your legs</b></div>
                      @endif
                      <script type="text/javascript">
                          onReady(function(){
                            var nextYear = moment.tz("{{Carbon::parse($associate->last_grace)->addMinutes($sup_as_rank->graced_minutes)}}", 'Africa/Lagos');
                            $('#clock').countdown(nextYear.toDate(), function(event) {
                              $(this).html(event.strftime('%D Days %H hrs : %M:%S'));
                            });
                          })
                      </script>
                  @else
                    <h2 style="font-size:18px;" class="font-light mb-0">
                      <b><span id="clock"></span></b>
                    </h2>
                    @if($associate->balance_leg)
                      <div class="text-danger blink"><b>Balance your legs</b></div>
                    @endif
                    <script type="text/javascript">
                        onReady(function(){
                          var nextYear = moment.tz("{{$associate->created_at->addMinutes($sup_as_rank->minutes)}}", 'Africa/Lagos');
                          $('#clock').countdown(nextYear.toDate(), function(event) {
                            $(this).html(event.strftime('%D Days %H hrs : %M:%S'));
                          });
                        })
                    </script>
                  @endif
                    <br>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-purple" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
  @endif
@endif

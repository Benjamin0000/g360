@php
use Carbon\Carbon;
use App\Http\Helpers;
$ppp_g_trail = Helpers::getRegData('ppp_grace_trail');
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
@if($user->ppp && $user->ppp->status != $ppp_g_trail)
 @php $ppp = $user->ppp @endphp
  <div class="col-lg-3 col-md-6">
      <div class="card">
          <div class="card-body">
                <h4 class="card-title"><span>PPP</span>  <span class="float-right">50kClub</span></h4>
                <div class="">
                    <h2 class="font-light" style="font-size:18px;">
                      <span class="">
                        <i class="fa fa-users text-success"></i><span> {{$user->totalNotPlaced()}}</span> : <span class="text-danger">{{$user->totalPlaced()}}</span>
                      </span>
                      <span class="float-right">
                         <i class="mdi mdi-trophy-award text-danger"></i>{{$ppp->point}}
                      </span>
                    </h2>
                    <span class="text-muted" style="min-height:17px;display:block;"></span>
                </div>
                @if($ppp->status == 0)
                  <div class="text-center" style="margin-top:-22px;"><b style="color:black;" id="ddp"></b></div>
                  @php
                    if($ppp->graced_at != ''){
                       $date = $ppp->graced_at;
                       $minutes = Helpers::getRegData('ppp_grace_minutes');
                    }else{
                      $date = $ppp->created_at;
                      $minutes = Helpers::getRegData('ppp_minutes');
                    }
                  @endphp
                  <script type="text/javascript">
                      onReady(function(){
                        var nextYear = moment.tz("{{Carbon::parse($date)->addMinutes($minutes)}}", 'Africa/Lagos');
                        $('#ddp').countdown(nextYear.toDate(), function(event) {
                          $(this).html(event.strftime('%D Days %H hrs : %M:%S'));
                        });
                      })
                  </script>
                @elseif($ppp->status == 2)
                  @php $rFee = Helpers::getRegData('ppp_r_fee'); @endphp
                  <form action="{{route('user.dashboard.rappp')}}" method="post" class="text-center" style="padding:2px;margin-top:-27px;">
                    @csrf
                    <button onclick="return confirm('You will pay a reactivation fee of {{$cur.number_format($rFee)}}')" class="btn btn-primary btn-sm">Reactivate</button>
                  </form>
              @endif
              <div class="progress">
                  <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
          </div>
      </div>
  </div>
@endif

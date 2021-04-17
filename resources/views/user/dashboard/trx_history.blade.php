@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex no-block">
                    <h4 class="card-title">Recent Transactions</h4>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table no-wrap table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Amount</th>
                                <th>Wallet</th>
                                <th>Desc.</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if($histories->count())
                            @php $count = 1;@endphp
                            @foreach($histories as $history)
                              <tr>
                                <td>{{$count++}}</td>
                                <td>
                                  @if($history->name != "cpv" &&
                                      $history->name != 'h_token' &&
                                      $history->name != 'award_point')
                                    {{$cur}}
                                  @endif
                                  {{number_format($history->amount,2,'.',',')}}
                                  @if($history->type == 'debit')
                                      <span class="badge badge-danger">Debit</span>
                                  @elseif($history->type == 'credit')
                                      <span class="badge badge-success">Credit</span>
                                  @endif
                                </td>
                                <td>
                                  @if($history->name == 'trx_balance')
                                    TRX-wallet
                                  @elseif($history->name == 'with_balance')
                                    W-Wallet
                                  @elseif($history->name == 'pkg_balance')
                                    PKG-Wallet
                                  @elseif($history->name == 'pend_balance')
                                    PEND-Wallet
                                  @elseif($history->name == 'cpv')
                                    POINT VALUE
                                  @elseif ($history->name == 'h_token')
                                    HEALTH TOKEN
                                  @elseif($history->name == 'award_point')
                                    POINT AWARD
                                  @endif
                                </td>
                                <td>{{$history->description}}</td>
                                <td>{{$history->created_at->isoFormat('lll')}}</td>
                              </tr>
                            @endforeach
                          @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

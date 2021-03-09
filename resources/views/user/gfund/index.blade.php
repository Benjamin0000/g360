@extends('user.layout', ['title'=>'Gfund'])
@section('content')
@php
  $cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
  $user = Auth::user();
  $last_pkg_id = 7;
@endphp
<style >
  #sec_f_to_sh{
    display: none;
  }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <br/>
                <div class="card-body">
                    <h4 class="card-title">Transfer Funds</h4>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#b" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Local Bank</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#o" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Other Members</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#w" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-wallet"></i></span> <span class="hidden-xs-down">W-Wallet</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#t" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-wallet"></i></span> <span class="hidden-xs-down">TRX-Wallet</span></a> </li>
                    </ul>
                    <!-- Tab panes -->
              <div class="tab-content tabcontent-border">
                <div class="tab-pane active  p-3 pt-5" id="b" role="tabpanel">
                    <form class="form-horizontal" >
                        <div class="form-group">
                            <label>Recipient Account-Number </label>
                            <input type="text" class="form-control" name="pin" />
                        </div>
                        <div class="form-group">
                            <label>Current Password </label>
                            <input type="text" class="form-control" name="pin" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary ml-2">Request Transfer </button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane p-3 pt-5" id="o" role="tabpanel" style="padding-top:10px !important;">
                    <div class="row">
                        <div class="col-md-6">
                          <div class="text-center">
                             <div style="font-size:25px;"><b>{{$cur}}<span class="bal" >{{number_format(Auth::user()->with_balance, 2, '.', ',')}}</span></b></div>
                             <small>Withdrawal Wallet</small>
                          </div>
                            <form  id='trf1_others'>
                                <div class="form-group">
                                  <label>Amount</label>
                                  <input  type="text" id="amt1" name="amount" required placeholder="amount" class="amt form-control" value="">
                                </div>
                                @csrf
                                <div class="form-group">
                                    <label>Recipient G-Number</label>
                                    <input type="text" id="rgnum" required class="form-control" name="gnumber">
                                </div>
                                <div id="trf1_othersE"></div>
                                <div class="form-group">
                                    <button type="submit" id="trf1b" class="btn btn-primary btn-block">Continue</button>
                                </div>
                            </form>
                            <div id='sec_f_to_sh'>
                                <form id="c_t_oth">
                                  <br>
                                     <div class="form-group">
                                         <h4>Confirm Transfer</h4>
                                         <div><b>Amount</b>: <span id="aaa"></span></div>
                                         <div><b>Receiver</b>: <span id="rrr"></span></div>
                                         <div><b>Username</b>: <span id="uuu"></span> </div>
                                         <div><b>G-number</b>: <span id="ggg"></span> </div>
                                     </div>
                                      @csrf
                                      <input type="hidden" id="s_amt" name="amount" value="">
                                      <input type="hidden" id="s_gnum" name="gnumber" value="">
                                      <div id="c_t_othE"></div>
                                      <div class="form-group">
                                          <button type="button" id="cancel_int_t" class="btn btn-danger">Cancel</button>  <button id="ctbtn" class="btn btn-primary">Continue</button>
                                      </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-3 pt-5" id="w" role="tabpanel" style="padding-top:10px !important;">
                <div class="row">
                    <div class="col-md-6">
                      <div class="text-center">
                         <div style="font-size:25px;"><b>{{$cur}}<span class="bal">{{number_format(Auth::user()->with_balance, 2, '.', ',')}}</span></b></div>
                         <small>Withdrawal Wallet</small>
                      </div>
                      <br>
                         <form id='wtf'  method="post">
                           <div class="form-group">
                             <label for="">Enter amount</label>
                             <input required type="text" id="amt" required name="amount" placeholder="amount" class="amt form-control" value="">
                           </div>
                           <div class="form-group">
                              <select class="wide sselect form-control"  name="wallet">
                                  <option value="">Select wallet</option>
                                  <option id="toTrx" value="tw">TRX-Wallet</option>
                                  @if($user->pkg_id != $last_pkg_id)
                                    <option value="pkg">PKG-Wallet</option>
                                  @endif
                              </select>
                           </div>@csrf
                           <div class="form-group">
                             <br>
                             <br>
                              <div id="intTe"></div>
                              <button id="itb" class="btn btn-primary btn-block">Continue</button>
                           </div>
                         </form>
                       </div>
                     </div>
                   </div>

                   <div class="tab-pane p-3 pt-5" id="t" role="tabpanel" style="padding-top:10px !important;">
                   <div class="row">
                       <div class="col-md-6">
                         <div class="text-center">
                            <div style="font-size:25px;"><b>{{$cur}}<span id="bal3">{{number_format(Auth::user()->trx_balance, 2, '.', ',')}}</span></b></div>
                            <small>Transaction Wallet</small>
                         </div>
                         <br>
                            <form id='wtt' method="post">
                              <div class="form-group">
                                <label for="">Enter amount</label>
                                <input required type="text" name="amount" placeholder="amount" class="amt form-control" value="">
                              </div>
                              <div class="form-group">
                                 <select class="wide sselect form-control"  name="wallet">
                                     <option value="">Select wallet</option>
                                     <option  value="w">W-Wallet</option>
                                     {{-- @if($user->pkg_id != $last_pkg_id)
                                       <option value="pkg">PKG-Wallet</option>
                                     @endif --}}
                                 </select>
                              </div>@csrf
                              <div class="form-group">
                                <br>
                                <br>
                                 <div id="intTte"></div>
                                 <button id="ittb" class="btn btn-primary btn-block">Continue</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('user.gfund.jsscript')
@endsection

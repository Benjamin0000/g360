@extends('user.layout', ['title'=>'Gfund'])
@section('content')
@php
  $cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
  $user = Auth::user();
  $last_pkg_id = 7;
@endphp
<style>#sec_f_to_sh{display: none;}</style>
<div class="col-12">
  <div class="card">
    <div class="card-body"> 
      <div class="card-body">
        <div class="row" style="padding:0;">
          <div class="col-md-7">
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
                <div class="text-center">
                  <div style="font-size:25px;"><b>{{$cur}}<span class="bal">{{number_format(Auth::user()->with_balance, 2, '.', ',')}}</span></b></div>
                  <small>Withdrawal Wallet</small>
                </div>
                <form method="POST" action="{{route('user.gfund.getBankAccountDetail')}}">
                  <div class="form-group">
                    <label for="">Amount</label>
                    <input type="text" required name="amount" value="" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Recipient Bank</label>
                    <select required  name="bank" class="form-control">
                      <option value="">Select</option>
                      @if($banks->count())
                        @foreach($banks as $bank)
                        <option value="{{$bank->code}}">{{$bank->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Account Number </label>
                    <input type="text" required class="form-control" name="account_number" />
                  </div>
                  @csrf
                  <input type="hidden" name="type" value="first">
                  <div class="form-group">
                    <label>Current Password </label>
                    <input type="password" required class="form-control" name="password" />
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary ml-2">Request Transfer </button>
                  </div>
                </form>
              </div>
              <div class="tab-pane p-3 pt-5" id="o" role="tabpanel" style="padding-top:10px !important;">
                    <div class="text-center">
                      <div style="font-size:25px;"><b>{{$cur}}<span class="bal" >{{number_format(Auth::user()->with_balance, 2, '.', ',')}}</span></b></div>
                      <small>Withdrawal Wallet</small>
                    </div>
                    <form id='trf1_others'>
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
              <div class="tab-pane p-3 pt-5" id="w" role="tabpanel" style="padding-top:10px !important;">
  
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
  
              <div class="tab-pane p-3 pt-5" id="t" role="tabpanel" style="padding-top:10px !important;">
  
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
              <br>
          </div>
          <div class="col-md-5">
            <h4 class="card-title text-center">Fund Wallet</h4>
            
            <div class="text-center">
              <b>{{$user->virtualAccount ? $user->virtualAccount->number : ''}}</b>
              <div><small>{{$user->virtualAccount ? $user->virtualAccount->bank_name : ''}}</small></div> 
              <div><small>Your virtual account number</small></div> 
              <br>
              <div>
                Transfer funds to the account number above to automatically fund your W-wallet
              </div>  
              <div class="text-danger">
                <small>
                  <i class="fa fa-info-circle"></i> Note this is an automatic process you don't need to contact support.
                </small>
              </div> 
              {{-- <br>
              <div>
                <a href="">View History</a>
              </div> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('user.gfund.jsscript')
@endsection

@extends('user.layout', ['title'=>'Loan'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Loan</h3>
    </div>
    <div class="col-md-6 col-4 align-self-center">
        <a href="{{route('user.loan.apply')}}" class="btn float-right hidden-sm-down btn-primary"><i class="mdi mdi-plus-circle"></i> Loan Application</a>
    </div>
</div>
  <div class="row">
      <!-- Column -->
      <div class="col-lg-6 col-md-6">
          <div class="card card-inverse card-primary">
              <div class="card-body">
                  <div class="d-flex">
                      <div class="mr-3 align-self-center">
                          <h1 class="text-white"><i class="ti-pie-chart"></i></h1></div>
                      <div>
                          <h3 class="card-title">Active Loan</h3>
                          <h6 class="card-subtitle">Ref:<em></em> <span class="float-right ml-2">Date: <em></em></span></h6>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-5 align-self-center">
                          <font class="display-7 text-white"></font>
                      </div>
                      <div class="col-7 align-self-center">
                          <img src="/assets/images/fee.png')" alt="" width="100px" />
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- Column -->
      <!-- Column -->
      <div class="col-lg-6 col-md-6">
          <div class="card">
              <div class="card-body">
                  <ul class="list-icons">
                      <li><b>Paid Amount:</b><span class="badge badge-success"></span></li>
                      <li><b>Balance:</b><span class="badge badge-danger mr-2"></span></li>
                      <li>Elapse Date: </li>
                      <li><a href="" class="btn btn-outline-success float-right">Pay Now</a></li>
                    </ul>
              </div>
          </div>
      </div>
      <!-- Column -->
  </div>
  <div class="row">
      <div class="col-lg-12">
          <div class="card">
              <div class="card-body">
                  <div class="d-flex no-block">
                      <h4 class="card-title">Loan History</h4>
                  </div>
                  <h6 class="card-subtitle">Request</h6>
                  <div class="table-responsive">
                      <table class="table stylish-table">
                          <thead>
                              <tr>
                                  <th>Status</th>
                                  <th>Amount</th>
                                  <th>Application/Review date</th>
                                  <th>Other information</th>
                              </tr>
                          </thead>
                          <tbody>

                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

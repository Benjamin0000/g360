@extends('user.layout', ['title'=>'Apply For Loan'])
@php
use Carbon\Carbon;
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
  <div class="card text-center">
      <div class="card-header">
          Apply for Loan
      </div>
      <div class="card-body">
          <center>
              <img src="/assets/images/loan.png?>" width="120px" />
          </center>
          <h4 class="card-title">Apply For a Loan</h4>
          <p class="card-text">Get low interest loan without collateral</p>
          <form class="form" method="post" action="">

              <div class="form-group mt-5 row">
                  <label for="example-text-input" class="col-2 col-form-label">Guarantor G-No.</label>
                  <div class="col-10">
                      <input required class="form-control" name="gno_1" type="text" placeholder="G - Number" value="" >
                      <label class="float-left text-danger"></label>
                  </div>
              </div>
              <div class="form-group mt-5 row">
                  <label for="example-text-input" class="col-2 col-form-label">Guarantor G-No.</label>
                  <div class="col-10">
                      <input required class="form-control" name="gno_2" type="text" placeholder="G - Number" value="" >
                      <label class="float-left text-danger"></label>
                  </div>
              </div>
              <div class="form-group row">
                  <label for="example-month-input2" class="col-2 col-form-label">Amount</label>
                  <div class="col-10">
                      <select required class="custom-select col-12" name="amount">
                          <option value="">Select amount...</option>
                          <option value="50000">N50,000</option>
                          <option value="100000">N100,000</option>
                          <option value="150000">N150,000</option>
                          <option value="200000">N200,000</option>
                          <option value="250000">N250,000</option>
                          <option value="500000">N500,000</option>
                          <option value="1000000">N1,000,000</option>
                      </select>
                      <label class="float-left text-danger"></label>
                  </div>
              </div>
              <div class="form-group row">
                  <label for="example-month-input2" class="col-2 col-form-label">Period</label>
                  <div class="col-10">
                      <select required class="custom-select col-12" name="period">
                          <option value="">Select interval...</option>
                          <option value="1">1 Month (5% interest)</option>
                          <option value="3">3 Month (5% interest) </option>
                          <option value="6">6 Month (15% interest) </option>
                          <option value="12">12 Month (20% interest) </option>
                      </select>
                      <label class="float-left text-danger"></label>
                  </div>
              </div>
              <div class="form-group mt-5 row">
                  <label for="example-text-input" name="name" class="col-2 col-form-label">Extra information</label>
                  <div class="col-10">
                      <textarea required class="form-control" type="text" name="extra"></textarea>
                      <label class="float-left text-danger"></label>
                  </div>
              </div>
              <div class="form-group mt-5">
                  <button class="btn btn-outline-primary float-right">Submit request </button>
              </div>
          </form>
      </div>
  </div>
@endsection

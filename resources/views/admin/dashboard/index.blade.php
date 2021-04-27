@extends('admin.layout', ['title'=>'Admin Dashboard'])
@section('content')
  @php
      use App\Http\Helpers;
      $cur = Helpers::LOCAL_CURR_SYMBOL;
  @endphp
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            {{-- <h4 class="text-themecolor">Dashboard</h4> --}}
        </div>
        {{-- <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard 1</li>
                </ol>
                <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
            </div>
        </div> --}}
    </div>


    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-user"></i></h3>
                                <p class="text-muted">TOTAL USERS</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$total_users}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fas fa-cash-o"></i></h3>
                                <p class="text-muted">VAT</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$cur.number_format($vat,2,'.',',')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <a href="{{route('admin.vat_history')}}">Settings</a>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fa fa-money"></i></h3>
                                <p class="text-muted">WITH BALANCE</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$cur.number_format($with_bal, 2, '.', ',')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fa fa-money"></i></h3>
                                <p class="text-muted">PEND BALANCE</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$cur.number_format($pend_bal, 2, '.', ',')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class=""></i></h3>
                                <p class="text-muted">TRX-BALANCE</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$cur.number_format($trx_bal, 2, '.', ',')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fas fa-cash-o"></i></h3>
                                <p class="text-muted">PKG BALANCE</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$cur.number_format($pkg_bal, 2, '.', ',')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fa fa-money"></i></h3>
                                <p class="text-muted">LOAN ELIG BALANCE</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$cur.number_format($loan_elig_bal, 2, '.', ',')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fa fa-money"></i></h3>
                                <p class="text-muted">SAVED LOAN BALANCE</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter">{{$cur.number_format($total_loan_bal, 2, '.', ',')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



  <div class="card col-md-3">
      <div class="card-body">
          <div class="row">
              <div class="col-md-12">
                  <div class="d-flex no-block align-items-center">
                      <div>
                          <h3><i class="fas fa-cash-o"></i></h3>
                          <p class="text-muted">GSTEAM FEE</p>
                      </div>
                      <div class="ml-auto">
                          <h2 class="counter">{{$cur.number_format($gsteam_fee,2,'.',',')}}</h2>
                      </div>
                  </div>
              </div>
              <div class="col-12">
                  <div class="progress">
                      <div class="" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
              </div>
          </div>
          <a href="{{route('admin.gs_fee')}}">Settings</a>
      </div>
  </div>






    {{-- <div class="row">
        <!-- Column -->
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex m-b-40 align-items-center no-block">
                        <h5 class="card-title ">YEARLY SALES</h5>
                    </div>

                </div>
            </div>
        </div>
    </div> --}}
@endsection

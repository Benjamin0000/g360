@extends('user.layout', ['title'=>'Packages'])
@section('content')
    <div class="row">
        <div class="col-12">
            @if(session('select_a_pkg'))
            <div class="alert alert-warning">
               <i class="fa fa-info-circle"></i> You have to select a sign up package before you can continue
            </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center text-success">Select a sign up package</h2>
                    <br>
                    <div class="row pricing-plan">
                        <div class="col-md-6 col-xs-12 col-sm-12 no-padding">
                            <div class="pricing-box border-left">
                                <div class="pricing-body">
                                    <div class="pricing-header">
                                        <h4 class="text-center">Probation</h4>
                                        <h2 class="text-center"><span class="price-sign">₦</span>Free</h2>
                                        <p class="uppercase">one time</p>
                                    </div>
                                    <div class="price-table-content">
                                        <div class="price-row"><i class="icon-user"></i> 3rd Gen. level earning </div>
                                        <div class="price-row"><i class="icon-screen-smartphone"></i> Low Loan facility</div>
                                        <div class="price-row"><i class="icon-health"></i> Low insurance</div>
                                        <div class="price-row">
                                            <a href="" class="btn btn-success waves-effect waves-light mt-3">Sign up</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-12 no-padding">
                            <div class="pricing-box featured-plan">
                                <div class="pricing-body">
                                    <div class="pricing-header">
                                        <h4 class="price-lable text-white bg-warning"> Popular</h4>
                                        <h4 class="text-center">Premium</h4>
                                        <h2 class="text-center"><span class="price-sign">₦</span>16,002</h2>
                                        <p class="uppercase">one time</p>
                                    </div>
                                    <div class="price-table-content">
                                        <div class="price-row"><i class="icon-user"></i> 15th Gen. level earning </div>
                                        <div class="price-row"><i class="icon-screen-smartphone"></i> Medium Loan facility</div>
                                        <div class="price-row"><i class="icon-health"></i> Medium insurance</div>
                                        <div class="price-row">
                                            <a href="" class="btn btn-success waves-effect waves-light mt-3">Select</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
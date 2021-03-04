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
                    <div class="row pricing-plan">
                        <div class="col-md-6 col-xs-12 col-sm-12 no-padding">
                            <div class="pricing-box border" style="min-height: 200px;">
                                <div class="pricing-body">
                                    <div class="pricing-header">
                                        <div class="text-center">Free</div>
                                    </div>
                                    <div class="price-table-content">
                                        <div class="price-row" style="padding:10px;">                                        
                                            You can sign up for free but you must perform a minimum 
                                            of ₦15,000 purchase transaction from the system within 
                                            15 days of sign up or make initial deposit of ₦3,000 into
                                            the fixed package wallet or the account will be suspended.
                                            Once you have performed any of the above transactions, 
                                            you can sponsor and earn from only your first
                                            generation  and you can be on the free
                                            package for a maximum period of 45 business days or
                                            you will be charged additional 30% of the premium package you want to sign up to.
                                        </div>
                                        <div class="price-row">
                                            <form action="{{route('user.package.select_free')}}" method="POST">
                                                @csrf
                                                <button class="btn btn-success waves-effect waves-light mt-3">Select</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-12 no-padding">
                            <div class="pricing-box border" >
                                <div class="pricing-body">
                                    <div class="pricing-header">
                                        {{-- <h4 class="price-lable text-white bg-warning"> Popular</h4> --}}
                                        <div class="text-center">Premium</div>
                                    </div>
                                    <div class="price-table-content">
                                        <div class="price-row" style="padding:10px;">                                        
                                            This is the second category of registration
                                            where members sign up for different paid packages
                                            and enjoy benefits such as Health insurance,
                                            Travel, Own a store online, etc. and you earn
                                            deep according to your sign up package.
                                        </div>
                                        <div class="price-row" style="margin-top:96px;">
                                            <a href="{{route('user.package.show_premium')}}" class="btn btn-success waves-effect waves-light mt-3">Select</a>
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
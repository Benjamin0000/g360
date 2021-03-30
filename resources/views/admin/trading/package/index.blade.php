@extends('admin.layout', ['title'=>'Package'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title ">PACKAGE</h5>
                </div>
                <div style="min-height: 340px;">
                  <div class="row">
                    <div class="col-md-4">
                      <h5 class="text-center"><b>CREATE PACKAGE</b></h5>
                      <form  action="{{route('admin.trading.createPackage')}}" method="post">
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input type="number" name="amount" value="" class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="">Interest in %</label>
                          <input type="text" name="interest" value="" class="form-control">
                        </div>
                         <div class="form-group">
                             <label for="">Name</label>
                             <input type="text" name="name" value="" class="form-control">
                         </div>
                         @csrf
                         <div class="form-group">
                             <label for="">PV</label>
                             <input type="number" name="pv" value="" class="form-control">
                         </div>
                         <div class="form-group">
                             <label for="">Referral PV</label>
                             <input type="number" name="referral_pv" value="" class="form-control">
                         </div>
                         <div class="form-group">
                             <label for="">Expiry Days</label>
                             <input type="number" name="expiry_days" value="" class="form-control">
                         </div>
                         <div class="form-group">
                           <label for="">Referral commission</label>
                           <input type="text" name="referral_commission" value="" class="form-control" placeholder="Eg: 1.3, 4.6, 5.3, 4.5">
                         </div>
                         <div class="form-group">
                             <button class="btn btn-primary">Create Package</button>
                         </div>
                      </form>
                    </div>
                    <div class="col-md-8">
                       <h5 class="text-center"><b>PACKAGES</b></h5>
                       <br>
                       <div class="table-responsive">
                          <table class="table table-bordered">
                             <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Name</th>
                                  <th>Amount</th>
                                  <th>Interest</th>
                                  <th>PV</th>
                                  <th>Referral PV</th>
                                  <th>Ref. Com</th>
                                  <th>Expiry Days</th>
                                  <th>Action</th>
                                </tr>
                             </thead>
                             <tbody>
                               @if($packages->count())
                                 @php $count = Helpers::tableNumber($total) @endphp
                                  @foreach($packages as $package)
                                    <tr>
                                       <td>{{$count++}}</td>
                                       <td>{{$package->name}}</td>
                                       <td>{{$cur}}{{number_format($package->amount)}}</td>
                                       <td>{{$package->interest}}%</td>
                                       <td>{{$package->pv}}</td>
                                       <td>{{$package->ref_pv}}</td>
                                       <td>{{$package->ref_percent}}</td>
                                       <td>{{$package->exp_days}}</td>
                                       <td>
                                         <button class="btn btn-info btn-sm">Edit</button>
                                         <button class="btn btn-danger btn-sm">Delete</button>
                                       </td>
                                    </tr>
                                  @endforeach
                               @endif
                             </tbody>
                          </table>
                       </div>
                       {{$packages->links()}}
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

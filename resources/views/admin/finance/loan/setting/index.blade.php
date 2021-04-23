@extends('admin.layout', ['title'=>'LOAN SETTINGS'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">LOAN</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.finance.loan.index')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title ">SETTINGS</h5>
                </div>
                <div class="row">
                    <div class="col-md-12">
                      <button style="margin:5px;" class="btn btn-primary float-right" data-toggle='modal' data-target='#add'>ADD</button>

                       <table class="table table-hover table-bordered">
                           <thead>
                             <tr>
                               <th>Name</th>
                               <th>Min</th>
                               <th>Max</th>
                               <th>Interests</th>
                               <th>Expiry Months</th>
                               <th>Action</th>
                             </tr>
                           </thead>
                           <tbody>
                             @if($loanPlans->count())
                               @foreach($loanPlans as $loanPlan)
                                 <tr>
                                   <td>{{$loanPlan->name}}</td>
                                   <td>{{$cur.number_format($loanPlan->min)}}</td>
                                   <td>{{$cur.number_format($loanPlan->max)}}</td>
                                   <td>
                                     {{$loanPlan->interest}}%
                                     <div>Grace: &nbsp;{{$loanPlan->f_interest}}%</div>
                                   </td>
                                   <td>
                                     {{$loanPlan->exp_months}} Months
                                     <div>Grace: &nbsp;{{$loanPlan->grace_months}} Months</div>
                                   </td>
                                   <td>
                                      <button data-toggle='modal' data-target='#edit{{$loanPlan->id}}' class="btn btn-info btn-sm">EDIT</button>
                                      <form style="display:inline;" action="{{route('admin.finance.deleteLoanPlan', $loanPlan->id)}}" method="post">
                                          @csrf
                                          @method('delete')
                                          <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure about this?')">DELETE</button>
                                      </form>
                                   </td>
                                 </tr>
                                 @include('admin.finance.loan.setting.edit_modal')
                               @endforeach
                             @endif
                           </tbody>
                       </table>
                    </div>
                </div>
                @include('admin.finance.loan.setting.add_modal')
            </div>
        </div>
    </div>
</div>
@endsection

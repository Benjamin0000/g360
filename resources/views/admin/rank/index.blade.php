@extends('admin.layout', ['title'=>'Ranks'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<style>tr{text-align:center;}</style>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">RANKS</h5>
                </div>
                <div class="table-responsive">
                 <table class="table table-bordered table-hover">
                   <thead>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>PV</th>
                        <th>Prize</th>
                        <th>Loan</th>
                        <th>Loan exp. months</th>
                        <th>Interest</th>
                        <th>LMP</th>
                        <th>LMP exp. Months</th>
                        <th>Action</th>
                      </tr>
                   </thead>
                   <tbody>
                     @if($ranks->count())
                       @php $count = Helpers::tableNumber($total) @endphp
                       @foreach($ranks as $rank)
                         <tr>
                           <td>{{$count++}}</td>
                           <td>
                             {{ucwords($rank->name)}}
                             @if($rank->id == 1)
                              <div>
                               <b class="text-danger">Fee {{$cur.number_format($rank->fee)}}</b>
                              </div>
                             @endif
                           </td>
                           <td>{{$rank->pv}}</td>
                           <td>
                             {{$cur.number_format($rank->prize)}}
                             <div class="text-danger">{{$cur.number_format($rank->carry_over)}}</div>
                            </td>
                           <td>{{$cur.number_format($rank->loan)}}</td>
                           <td>
                             {{$rank->loan_exp_m}} months
                             <div>Grace: {{$rank->loan_g_exp_m}} months</div>
                           </td>
                           <td>
                             {{$rank->loan_interest}}%
                             <div>Grace: {{$rank->loan_g_interest}}%</div>
                           </td>
                           <td>{{$cur.number_format($rank->total_lmp)}}</td>
                           <td>{{$rank->lmp_months}} months</td>
                           <td>
                             <button class="btn btn-info btn-sm" data-toggle='modal' data-target='#edit{{$rank->id}}'>EDIT</button>
                           </td>
                         </tr>
                         @include('admin.rank.edit_modal')
                       @endforeach
                    @endif
                   </tbody>
                 </table>
               </div>
               @if(!$ranks->count())
                  <div class="alert alert-warning">
                    Nothing to show
                  </div>
               @endif
            </div>
        </div>
    </div>
</div>
@endsection

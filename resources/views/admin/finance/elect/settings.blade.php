@extends('admin.layout', ['title'=>'Electricity'])
@section('content')
@php
  use App\Http\Helpers;
  $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Electricity</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.finance.disco.index')}}" class="btn btn-info d-none d-lg-block m-l-15"> Back</a>
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
                <div style="min-height:340px;">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr>
                         <th>No</th>
                         <th>Name</th>
                         <th>Charge</th>
                         <th>Comm. amt</th>
                         <th>Ref. amt</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                       @if($discos->count())
                         @php $count = 1; @endphp
                         @foreach($discos as $disco)
                           <tr>
                             <td>{{$count++}}</td>
                             <td>{{$disco->name}}</td>
                             <td>{{$cur.$disco->charge}}</td>
                             <td>{{$cur.$disco->comm_amt}}</td>
                             <td>{{$disco->ref_amt}}</td>
                             <td><button class="btn btn-info btn-sm" data-toggle='modal' data-target='#edit{{$disco->id}}'>EDIT</button></td>
                           </tr>
                           @include('admin.finance.elect.edit_disco_modal')
                         @endforeach
                       @endif
                     </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

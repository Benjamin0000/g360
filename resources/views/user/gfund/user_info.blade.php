@extends('user.layout', ['title'=>'Gfund'])
@section('content')
@php
  $cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
  $user = Auth::user();
  $last_pkg_id = 7;
@endphp
<style >
  #sec_f_to_sh{
    display: none;
  }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <br/>
                <div class="card-body">
                    <h4 class="card-title">Transfer Confirmation</h4>
                    <div>Amount: {{$cur.number_format($amount)}}</div>
                    <div>Receipient Name: {{$receiver['name']}}</div>
                    <div>Account Number: {{$number}}</div>
                    <div>Bank: {{$bank->name}}</div>
                    <div>VAT: {{$cur.$vat}}</div>
                    <form action="{{route('user.gfund.getBankAccountDetail')}}" method="post">
                        <input type="hidden" name="amount" value="{{$amount}}">
                        <input type="hidden" name="bank" value="{{$bank->code}}">
                        <input type="hidden" name="account_number" value="{{$number}}">
                        <input type="hidden" name="type" value="sec">
                        <input type="hidden" name="password" value="{{$password}}">
                        <br>
                        @csrf
                        <a href="{{route('user.gfund.index')}}" class="btn btn-danger">Cancel</a>
                        <button class="btn btn-success">Continue</button>
                    </form>
                </div>
        </div>
    </div>
</div>
</div>
@endsection

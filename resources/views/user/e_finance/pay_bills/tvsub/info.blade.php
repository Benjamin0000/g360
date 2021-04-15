@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<form action="{{route('user.pay_bills.finishSubTv')}}" method="post">
  <div class="form-group">
      <h4>Confirmation</h4>
      <div><b>Name</b>: {{$data['name']}}</div>
      <div><b>Address</b>: {{$data['address']}}</div>
      <div><b>Provider</b>: {{$data['disco']}}</div>
      <div><b>Smart card number</b>: {{$data['number']}}</div>
      <div><b>Amount</b>: {{$cur.number_format($data['amt'])}}</div>
      <div><b>Charge</b>: {{$cur.$data['charge']}}</div>
      <div><b>Total</b>: {{$cur.number_format($data['amt'] + $data['charge'])}}</div>
  </div>
 <a href="{{route('user.pay_bills.tvSub.index')}}" class="btn btn-danger">Cancel</a>
 <button class="btn btn-primary">Continue</button>
</form>

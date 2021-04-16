@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<form action="{{route('user.pay_bills.finishSubTv')}}" method="post">
  <div class="form-group">
      <h4>Confirmation</h4>
      <div><b>Name</b>: {{$data['name']}}</div>
      <div><b>Provider</b>: {{$data['provider']['name']}}</div>
      <div><b>Smart card number</b>: {{$data['number']}}</div>
      <div><b>Amount</b>: {{$cur.number_format($data['package']['topup_value'])}}</div>
      <div><b>Charge</b>: {{$cur.$data['provider']['charge']}}</div>
      <div><b>Total</b>: {{$cur.number_format($data['package']['topup_value'] + $data['provider']['charge'])}}</div>
  </div>
  @csrf
  <input type="hidden" name="service" value='{{$data['provider']['code']}}'>
  <input type="hidden" name="smartcard_number" value='{{$data['smart_card']}}'>
  <input type="hidden" name="product_code" value='{{$data['package']['code']}}'>
 <a href="{{route('user.pay_bills.tvSub.index')}}" class="btn btn-danger">Cancel</a>
 <button class="btn btn-primary">Continue</button>
</form>

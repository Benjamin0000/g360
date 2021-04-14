@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<form action="{{route('user.pay_bills.elect.buy')}}" method="post">
  <input type="hidden" name="type" value="2">
  <input type="hidden" name="disco" value="{{$data['service']}}">
  <input type="hidden" name="amount" value="{{$data['amt']}}">
  <input type="hidden" name="meter_number" value="{{$data['number']}}">
  <div class="form-group">
      <h4>Confirmation</h4>
      <div><b>Name</b>: {{$data['name']}}</div>
      <div><b>Address</b>: {{$data['address']}}</div>
      <div><b>Disco</b>: {{$data['disco']}}</div>
      <div><b>Amount</b>: {{$cur.number_format($data['amt'])}}</div>
  </div>
  @csrf
  <div class="form-group">
    <button class="btn btn-primary" name="button">Continue</button>
  </div>
</form>

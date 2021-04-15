@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="form-group">
  <label for="">Select Package</label>
  <select class="form-control" name="package" required>
    @foreach ($plans as $plan)
      <option value="{{$plan['code']}}">{{$plan['name']}} {{$cur.number_format($plan['price'])}}</option>
    @endforeach
  </select>
</div>

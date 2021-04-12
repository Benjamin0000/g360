@php
use App\Http\Helpers;
$cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="form-group">
  <select class="form-control" name="plan">
    <option value="">Select plan</option>
    @if(count($products))
      @foreach ($products as $product)
        <option value="{{$product['product_id']}},{{$product['data_amount']}},{{$product['denomination']}},{{$product['validity']}}">
          {{$product['data_amount']}}MB {{$product['validity']}} {{$cur.number_format($product['denomination'])}}
        </option>
      @endforeach
    @endif
  </select>
</div>

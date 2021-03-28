<div class="form-group">
<select name="location" class="form-control">
  <option value="">Select Location</option>
  @foreach($cities as $city)
  	<option value="{{$city}}">{{$city}}</option>
  @endforeach
</select>
</div>
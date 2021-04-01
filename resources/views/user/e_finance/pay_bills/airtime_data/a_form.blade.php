<form class="" action="" method="post">
    <div class="form-group">
       <input type="text" class="form-control fc" name="" value="" placeholder="Mobile Number">
    </div>
    <div class="form-group">
      <div style="font-size:15px;">Select network provider</div>
      <div class="n-img-c">
        @foreach($airtimes as $airtime)
          <img src="{{asset($airtime->logo)}}" alt="" class="n-img">
        @endforeach
      </div>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
          <span class="input-group-text cur" style="background:#fff !important;">{{$cur}}</span>
      </div>
      <input type="text" placeholder="Amount" class="form-control fc" name="" value="">
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-block">Buy</button>
    </div>
</form>

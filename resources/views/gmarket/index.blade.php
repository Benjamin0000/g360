@include('includes.header', ['title'=>'General Market'])
<style>
.gcon{
  border-color:#e6e6e6;
  min-height: 100px;
  padding: 11px;
  border: 1px solid #e6e6e6;
  border-radius: 4px;
  text-align:center;
  margin-bottom:10px;
  display: block;
}
.gcon:hover{
  border-color:blue;
  box-shadow: 0 6px 12px rgb(0 0 0 / 10%);
}
</style>
	<div class="page-title-area bg-2">
		<div class="container">
			<div class="page-title-content">
				<h2>General Market</h2>
				{{-- <ul>
					<li>
						<a href="/">
							Home
						</a>
					</li>
					<li class="active">About us</li>
				</ul> --}}
			</div>
		</div>
	</div>
  <br>
  <div class="container">
    <h3 class="text-center">Browse Categories</h3>
  <div class="row">
    <div class="col-md-3">
      <a href="#{{--route('eshop.index')--}}" class="gcon">
          <img class="" width="72" src="/assets/shop.png" alt="Restaurants">
          <h3 class="">E-Shop</h3>
      </a>
    </div>

    <div class="col-md-3">
      <a href="#" class="gcon">
        <img class="" width="72" src="/assets/res.png" alt="Restaurants">
        <h3 class="">Restaurants</h3>
      </a>
    </div>

    <div class="col-md-3">
      <a href="#" class="gcon">
          <img class="" width="72" src="/assets/serve.png" alt="Restaurants">
          <h3 class="">Services</h3>
      </a>
    </div>

    <div class="col-md-3">
      <a href="#" class="gcon">
          <img class="" width="72" src="/assets/deliv.webp" alt="Restaurants">
          <h3 class="">Logistics</h3>
      </a>
    </div>

    <div class="col-md-3">
      <a href="#" class="gcon">
          <img class="" width="72" src="/assets/night.png" alt="Restaurants">
          <h3 class="">Nightlife</h3>
      </a>
    </div>
  </div>
</div>
<br>
@include('includes.footer')

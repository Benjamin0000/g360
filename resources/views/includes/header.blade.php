<!doctype html>
<html lang="en">
<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/bootstrap.min.css">
		<!-- Owl Theme Default CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/owl.theme.default.min.css">
		<!-- Owl Carousel CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/owl.carousel.min.css">
		<!-- Owl Magnific CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/magnific-popup.css">
		<!-- Animate CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/animate.css">
		<!-- Boxicons CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/boxicons.min.css">
		<!-- Flaticon CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/flaticon.css">
		<!-- Meanmenu CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/meanmenu.css">
		<!-- Nice Select CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/nice-select.css">
		<!-- Odometer CSS-->
		<link rel="stylesheet" href="/assets/frontpage/css/odometer.css">
		<!-- Style CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/style.css">
		<!-- Responsive CSS -->
		<link rel="stylesheet" href="/assets/frontpage/css/responsive.css">
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="/assets/frontpage/img/favicon.png">
		<!-- Title -->
		<link href="/assets/css/style.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/colors/blue.css" rel="stylesheet" type="text/css" />
		<title>{{$title}}</title>
		<style>@media screen and (min-width:992px){.m-auth{display:none;}}</style>
		<style >
		.gcon{
		  border-color:#e6e6e6;
		  min-height: 100px;
		  padding: 11px;
		  border: 1px solid #e6e6e6;
		  border-radius: 4px;
		  margin-bottom:10px;
		  text-align: center;
		}
		.gcon:hover{
		  border-color:blue;
		  box-shadow: 0 6px 12px rgb(0 0 0 / 10%);
		}
		</style>
    </head>
    <body>
		<!-- Start Preloader Area -->
		<div class="loader-wrapper">
			<div class="loader"></div>
			<div class="loader-section section-left"></div>
			<div class="loader-section section-right"></div>
		</div>
		<!-- End Preloader Area -->

		<!-- Start Heder Area -->
		<header class="header-area fixed-top">
			<div class="top-header-area">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-6 col-sm-6">
							<ul class="header-content-left">
								<li>
									<a href="mailto:hello@surety.com">
										<i class="bx bx-envelope"></i>
										Email: hello@getsupport360.com
									</a>
								</li>

								<li>
									<a href="tel:+61-821-456">
										<i class="bx bx-phone-call"></i>
										Call Us: +61-821-456
									</a>
								</li>
							</ul>
						</div>

						<div class="col-lg-6 col-sm-6">
							<ul class="header-content-right">
								<li>
									Opening Hour: 8:00 AM - 8:00 PM
								</li>

							</ul>
						</div>
					</div>
				</div>
			</div>

			<!-- Start Navbar Area -->
			<div class="nav-area">
				<div class="navbar-area">
					<!-- Menu For Mobile Device -->
					<div class="mobile-nav">
						<a href="/" class="logo">
						<img src="/assets/frontpage/img/logo.png" alt="Logo">
						</a>
					</div>

					<!-- Menu For Desktop Device -->
					<div class="main-nav">
						<nav class="navbar navbar-expand-md">
							<div class="container">
								<a class="navbar-brand" href="/">
									<img src="/assets/frontpage/img/logo.png" alt="Logo">
								</a>

								<div class="collapse navbar-collapse mean-menu">
									<ul class="navbar-nav m-auto">
										<li class="nav-item">
											<a href="{{route('front.index')}}" class="nav-link ">
												Home
											</a>
										</li>

										<li class="nav-item">
											<a href="{{route('front.services')}}" class="nav-link">
												Our Services
											</a>
										</li>

										<li class="nav-item">
											<a href="#how_it_works" class="nav-link">
												How It Works
											</a>
										</li>

										<li class="nav-item">
											<a href="{{route('front.about')}}" class="nav-link">About Us</a>
										</li>


										<li class="nav-item">
											<a href="{{route('gm.index')}}" class="nav-link">
												G-Market
											</a>
										</li>

										<li class="nav-item m-auth">
											<a href="{{route('login')}}" class="nav-link">
												 Login <span class='fa fa-lock' ></span>
											</a>
										</li>

										<li class="nav-item m-auth">
											<a href="{{route('register')}}" class="nav-link">
												 Sign up <span class='fa fa-user' ></span>
											</a>
										</li>
									</ul>

									@guest
									<!-- Start Other Option -->
									<div class="others-option">
										<div class="get-quote">
											<a href="{{route('login')}}" class="default-btn" style="background-color:transparent">
												Sign In
											</a>
											<a href="{{route('register')}}" class="default-btn">
												Create Account
											</a>
										</div>
									</div>
									<!-- End Other Option -->
									@else
									<div class="others-option">
										<div class="get-quote">
											<a href="{{route('user.dashboard.index')}}" class="default-btn">
												My Dashboard
											</a>
										</div>
									</div>
									@endguest
								</div>
							</div>
						</nav>
					</div>
				</div>
			</div>
			<!-- End Navbar Area -->
		</header>
		<!-- End Heder Area -->

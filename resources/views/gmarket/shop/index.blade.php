<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="Anil z" name="author">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
<meta name="keywords" content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">
<title>G-Store</title>
<!-- Favicon Icon -->
<link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.png">
<!-- Animation CSS -->
<link rel="stylesheet" href="/assets/assets/css/animate.css">
<!-- Latest Bootstrap min CSS -->
<link rel="stylesheet" href="/assets/assets/bootstrap/css/bootstrap.min.css">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<!-- Icon Font CSS -->
<link rel="stylesheet" href="/assets/assets/css/all.min.css">
<link rel="stylesheet" href="/assets/assets/css/ionicons.min.css">
<link rel="stylesheet" href="/assets/assets/css/themify-icons.css">
<link rel="stylesheet" href="/assets/assets/css/linearicons.css">
<link rel="stylesheet" href="/assets/assets/css/flaticon.css">
<link rel="stylesheet" href="/assets/assets/css/simple-line-icons.css">
<!--- owl carousel CSS-->
<link rel="stylesheet" href="/assets/assets/owlcarousel/css/owl.carousel.min.css">
<link rel="stylesheet" href="/assets/assets/owlcarousel/css/owl.theme.css">
<link rel="stylesheet" href="/assets/assets/owlcarousel/css/owl.theme.default.min.css">
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="/assets/assets/css/magnific-popup.css">
<!-- Slick CSS -->
<link rel="stylesheet" href="/assets/assets/css/slick.css">
<link rel="stylesheet" href="/assets/assets/css/slick-theme.css">
<!-- Style CSS -->
<link rel="stylesheet" href="/assets/assets/css/style.css">
<link rel="stylesheet" href="/assets/assets/css/responsive.css">
</head>
<body>
<!-- LOADER -->
<div class="preloader">
   <div class="lds-ellipsis">
       <span></span>
       <span></span>
       <span></span>
   </div>
</div>
<!-- END LOADER -->

<!-- Home Popup Section -->
@include('gmarket.shop.include.subscribe_pop_up')
<!-- End Screen Load Popup Section -->

<!-- START HEADER -->
@include('gmarket.shop.include.header')
<!-- END HEADER -->

<!-- START SECTION BANNER -->
@include('gmarket.shop.include.header_right_banner')
<!-- END SECTION BANNER -->

<!-- END MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
@include('gmarket.shop.include.exclusive_product')
<!-- END SECTION SHOP -->

<!-- START SECTION BANNER  ADS-->
@include('gmarket.shop.include.3_banner')
<!-- END SECTION BANNER ADS-->

<!-- START SECTION DEALS -->
@include('gmarket.shop.include.deals')
<!-- END SECTION DEALS -->

<!-- START SECTION TRDNDING -->
@include('gmarket.shop.include.trending')
<!-- END SECTION TRENDING -->

<!-- START SECTION BRAND LOGO -->
@include('gmarket.shop.include.brands')
<!-- END SECTION BRAND LOGO -->

<!-- START SECTION FEATURED -->
@include('gmarket.shop.include.featured')
<!-- END SECTION FEATURED -->

<!-- START SECTION SUBSCRIBE NEWSLETTER -->
@include('gmarket.shop.include.subscribe')
</div>
@include('gmarket.shop.include.footer')

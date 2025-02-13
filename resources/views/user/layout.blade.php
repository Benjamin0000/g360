<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{$title}} | GetSupport360</title>
    <link rel="icon" type="image/png" href="/assets/frontpage/img/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/css/style.css?v=2" rel="stylesheet" type="text/css" />
    <link href="/assets/css/colors/blue.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <script src="https://kit.fontawesome.com/bb8f3fac95.js" crossorigin="anonymous"></script>
    @php $rewds = Auth::user()->rewards() @endphp
    <script>
    var baseurl = "/";
    function onReady(yourMethod) {
    var readyStateCheckInterval = setInterval(function() {
        if (document && document.readyState === 'complete') { // Or 'interactive'
        clearInterval(readyStateCheckInterval);
        yourMethod();
        }
    }, 10);
    }
    </script>
    <style>
        .pricing-box:hover{
            background:#5c9abe;
            color:white !important;
            transition: 0.3s ease;
        }
        .pricing-header > div{
            font-size:20px;
            font-weight: bold;
        }
        .form-control{
            /* box-shadow: none !important; */
        }
        #rews{
          background:#F523EE;
          color:white;
          padding:5px;
          border-radius:50%;
          float:right;
          width:30px;
          font-weight: bolder;
          text-align: center;
        }
    </style>
    <style type="text/css">
    .lds-ripple{display:inline-block;position:relative;width:80px;height:80px}.lds-ripple div{position:absolute;border:4px solid #bb5de0;opacity:1;border-radius:50%;animation:lds-ripple 1s cubic-bezier(0,.2,.8,1) infinite}.lds-ripple div:nth-child(2){animation-delay:-.5s}@keyframes lds-ripple{0%{top:36px;left:36px;width:0;height:0;opacity:1}100%{top:0;left:0;width:72px;height:72px;opacity:0}}
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head><body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <!-- Logo icon -->
                        <b>
                            <img src="/assets/images/logo.png" alt="homepage" class="dark-logo" style="width: 65%" />
                        </b>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left-circle"></i></a> </li>
                        @if(Auth::user()->partner)
                          <li class="nav-item">
                              <a class="nav-link" href="{{route('user.partnership.index')}}">
                                 <span class="btn btn-success">Partner</span>
                              </a>
                          </li>
                        @endif
                          <li class="nav-item">
                              <a class="nav-link" href="{{route('user.agent.index')}}">
                                <span class="btn btn-danger">Agent</span>
                              </a>
                          </li>
                        {{-- <li class="nav-item hide"> <a class="nav-link text-muted " href="javascript:void(0)"><i class="ti-user"></i> 123456789 </a> </li> --}}
                    </ul>

                </div>
            </nav>
        </header>

        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                <div class="user-profile">
                    <!-- User profile image -->
                    <div class="profile-img">
                        <img src="/assets/images/default.png" alt="user" />
                    </div>
                    <!-- User profile text-->
                    <div class="profile-text"> <a href="#" class="dropdown-toggle link u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">@ {{Auth::user()->username}}<span class="caret"></span></a>
                        <div class="dropdown-menu animated flipInY">
                            <a href="{{route('user.profile.index')}}" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                            <div class="dropdown-divider"></div> <a href="{{route('user.settings.index')}}" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
                            <div class="dropdown-divider"></div> <a href="javascript:void(0)" class="dropdown-item lou_btn"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </div>
                    <h6>G- No. : {{Auth::user()->gnumber}} </h6>
                </div>
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li>
                            <a href="{{route('user.dashboard.index')}}" >
                                <i class="mdi mdi-gauge"></i>
                                <span class="hide-menu">Dashboard </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user.gfund.index')}}" aria-expanded="false" >
                                <i class="mdi mdi-trending-up"></i>
                                <span class="hide-menu">G - Funds </span>
                            </a>
                        </li>
                        <li>
                            <a class="" href="{{route('user.efinance.index')}}" aria-expanded="false">
                              <i class="mdi mdi-cash"></i>
                              <span class="hide-menu">E-Finance</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('user.gsclub.index')}}" aria-expanded="false" >
                                <i class="fa fa-users"></i>
                                <span class="hide-menu">Gs-Team </span>
                            </a>
                        </li>
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-vector-polyline"></i><span class="hide-menu">Downline</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{route('user.downline.direct')}}">Direct Referals</a></li>
                                <li><a href="{{route('user.downline.indirect')}}">Indirect Referal</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{route('user.reward.index')}}" >
                                <i class="mdi mdi-ticket"></i>
                                <span class="hide-menu">Reward
                                @if($rewds)
                                  <span id="rews" class="blink">{{$rewds}}</span> </span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user.trading.index')}}" aria-expanded="false" >
                                <i class="mdi mdi-trending-up"></i>
                                <span class="hide-menu">Trading </span>
                            </a>
                        </li>
                        <li >
                            <a href="{{route('user.loan.index')}}" >
                                <i class="mdi mdi-textbox"></i>
                                <span class="hide-menu">Loan </span>
                            </a>
                        </li>

                        <li >
                            <a href="{{route('user.epin.index')}}" >
                                <i class="mdi mdi-vector-arrange-above"></i>
                                <span class="hide-menu">E - Pin </span>
                            </a>
                        </li>
                        <li>
                            <a href="#" >
                                <i class="fa  fa-medkit"></i>
                                <span class="hide-menu">Insurrance</span>
                            </a>
                        </li>
                        <li>
                            <a class="" href="{{route('user.gmarket.index')}}" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">G-Market</span></a>
                        </li>

                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-history"></i><span class="hide-menu">Transaction History</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{route('user.history.w_wallet')}}">W-Wallet</a></li>
                                <li><a href="{{route('user.history.p_wallet')}}">P-wallet</a></li>
                                <li><a href="{{route('user.history.t_wallet')}}">T-Wallet</a></li>
                                <li><a href="{{route('user.history.pkg_wallet')}}">PKG-Wallet</a></li>
                            </ul>
                        </li>

                        <!--<li>
                            <a href="/user/bank" >
                                <i class="mdi mdi-widgets"></i>
                                <span class="hide-menu">Bank </span>
                            </a>
                        </li>-->

                        <li>
                            <a href="{{route('user.support.index')}}" >
                                <i class="mdi mdi-help"></i>
                                <span class="hide-menu">Support </span>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item-->
                <a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
                <!-- item-->
                <a href="" class="link" data-toggle="tooltip" title="Notification"><i class=" far fa-bell"></i></a>
                <!-- item-->
                <a href="javascript:void(0)" class="link lou_btn" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
            </div>
            <!-- End Bottom points-->
        </aside>
        <form action="{{route('logout')}}" method="post" id='lou_f'>
            @csrf
         </form>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
<div class="container-fluid" style="padding:20px 20px;">
    @if(session('pkg_activated'))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {!!session('pkg_activated')!!}</div>
    @elseif(session('error'))
        <div class="alert alert-danger"><i class="fa fa-info-circle"></i> {!!session('error')!!}</div>
    @elseif(session('success'))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {!!session('success')!!}</div>
    @elseif(session('warning'))
        <div class="alert alert-warning"><i class="fa fa-info-circle"></i> {!!session('warning')!!}</div>
    @elseif(session('choose_pkg'))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> Account activated, please select a package to continue</div>
    @elseif ($errors->any())
      <div class="alert alert-danger">
         <ul>
           @foreach ($errors->all() as $error)
             <li>{{$error}}</li>
           @endforeach
         </ul>
      </div>
    @endif
    @yield('content')
</div>
<footer class="footer">
    © {{date('Y')}} GETsupport360 Team
</footer>
</div>
</div>
<script type='text/javascript' src='/assets/plugins/jquery/jquery.min.js'></script>
<script type='text/javascript' src='/assets/plugins/bootstrap/js/popper.min.js'></script>
<script type='text/javascript' src='/assets/plugins/bootstrap/js/bootstrap.min.js'></script>
<script type='text/javascript' src='/assets/js/jquery.slimscroll.js'></script>
<script type='text/javascript' src='/assets/js/waves.js'></script>
<script type='text/javascript' src='/assets/js/sidebarmenu.js'></script>
<script type='text/javascript' src='/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js'></script>
<script type='text/javascript' src='/assets/js/custom.js'></script>
<script type='text/javascript' src='/assets/plugins/styleswitcher/jQuery.style.switcher.js'></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha512-NqYds8su6jivy1/WLoW8x1tZMRD7/1ZfhWG/jcRQLOzV1k1rIODCpMgoBnar5QXshKJGV7vi0LXLNXPoFsM5Zg==" crossorigin="anonymous"></script>
<script type='text/javascript' src='/assets/plugins/datatables.net/js/jquery.dataTables.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js" integrity="sha512-lteuRD+aUENrZPTXWFRPTBcDDxIGWe5uu0apPEn+3ZKYDwDaEErIK9rvR0QzUGmUQ55KFE2RqGTVoZsKctGMVw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data.min.js" integrity="sha512-rjmacQUGnwQ4OAAt3MoAmWDQIuswESNZwYcKC8nmdCIxAVkRC/Lk2ta2CWGgCZyS+FfBWPgaO01LvgwU/BX50Q==" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
  $('.sselect').niceSelect();
});
$(".lou_btn").click(function(){
  $("#lou_f").submit();
});
@if( session('login_success') )
  swal({
    title: "Welcome back!",
    text: "{{Auth::user()->fname}}",
    icon: "success",
    button: "Thank you",
  });
@endif
$('#table_id').DataTable({
    "aLengthMenu": [[ 50, 75, -1], [ 50, 75, "All"]],
    "responsive" : true,
});
function blink_text() {
    $('.blink').fadeOut(500);
    $('.blink').fadeIn(500);
}
setInterval(blink_text, 2000);
</script>
</body>
</html>

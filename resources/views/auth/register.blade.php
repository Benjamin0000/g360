<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sign up | GetSupport360 </title>
    <link href="/favicon.png" rel="shortcut icon" type="image/ico" />
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/colors/blue.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/png" href="/assets/frontpage/img/favicon.png">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style>
  .pass-graybar{height:3px;background-color:#ccc;width:100%;position:relative}.pass-colorbar{height:3px;background-image:url(passwordstrength.jpg);position:absolute;top:0;left:0}.pass-percent,.pass-text{font-size:1em}.pass-percent{margin-right:5px}
  .form-control{box-shadow:none!important}.emojione{width:20px;height:20px}.pass-strength-visible input.form-control,input.form-control:focus{border-bottom-right-radius:0;border-bottom-left-radius:0}.pass-strength-visible .pass-graybar,.pass-strength-visible .pass-colorbar,.form-control:focus+.pass-wrapper .pass-graybar,.form-control:focus+.pass-wrapper .pass-colorbar{border-bottom-right-radius:4px;border-bottom-left-radius:4px}
</style>
<body>
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
          <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <section id="wrapper" class="login-register login-sidebar"  style="background-image:url(/assets/images/background/login-register.jpg);">
      <div class="login-box card">
        <div class="card-body">
          <a href="/" class="text-center db"><img src="/assets/images/logo.png" alt="logo" style="width:140px" /></a>
          <form id="signupform" class="form-horizontal needs-validation" style="display:block" action="#">
            <h3 class="box-title mt-3 mb-0">Register Now</h3>
            <small>Create your account and enjoy</small>
            <div style="margin:6px;"></div>
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group mt-3">
              <label class="form-label control-label">Title</label>
              <div class="col-xs-12">
                <label class="radio-inline"><input type="radio" id="title" name="title" value="mr" checked> Mr</label>
                <label class="radio-inline"><input type="radio" id="title" name="title" value="mrs"> Mrs</label>
                <label class="radio-inline"><input type="radio" id="title" name="title" value="miss"> Miss</label>
              </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="fname">First name</label>
                    <input type="text" class="form-control" id="fname" name="fname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lname">Last name</label>
                    <input type="text" class="form-control" id="lname" name="lname" required>
                </div>
            </div>
            <div class="form-group">
                <label for="sponsor">Sponsor G-Number</label>
                <input type="text" value="{{App\Http\Helpers::defaultGnum()}}" class="form-control" id="sponsor" name="sponsor" required>
            </div>
            <div class="form-group">
                <label for="pg">Place under</label>
                <input type="text" name="pg" value="" class="form-control" placeholder="G-number" id="pg">
            </div>
            <div class="form-group">
                <label for="phone">Phone number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">New password</label> <small><a href="javascript:void(0)" class="float-right ssshow">Show</a></small>
                <input type="password" class="form-control password" id="password" name="password" required>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm password</label>
              <input type="password" class="form-control password" id="confirm_password" name="confirm_password" placeholder="Retype your password" required>
            </div>
            <h6><small id="err_zone" class="text-danger"></small></h6>
            <small>
              By clicking "Signup", you agree to have read and consent to our <a href="{{route('front.terms')}}">Terms</a>
            </small>
            <div class="form-group text-center mt-3">
              <div class="col-xs-12">
                <button id="loginbtn" class="btn btn-info waves-effect waves-light" type="submit">Create account</button>
              </div>
            </div>
            <div class="form-group mb-0">
              <div class="col-sm-12 text-center">
                <p>Already have an account? <a href="{{route('login')}}" class="text-info ml-1"><b>Sign In</b></a></p>
              </div>
            </div>
          </form>
          <div id="vform" class="form-horizontal" data-forward="false" style="display:none">
          <h3 class="box-title mt-3 mb-0 text-center">Verify Email Address </h3>
            <br/>
            <div class="card">
                <div class="card-body">
                    A verification email has been sent to you via email. Kindly open it and click on the link to complete your account.
                </div>
            </div>
            <input type="hidden" id="reference" name="reference" >
            <div class="text-center"><p id="ss_zone2" class="text-success"></p></div>
            <small id="err_zone2" class="text-danger"></small>
            {{-- <div class="form-group mb-0">
              <div class="col-sm-12">
                <p>Didn't get the code? <a href="javascript:void(0)" id="rsend" class="text-info ml-1"><b>Resend Email</b></a> </p>
              </div>
            </div> --}}
            <div class="form-group mb-0">
              <div class="col-sm-12 text-center">
                <p><a href="{{route('login')}}" class="text-info ml-1"><b>Sign In</b></a> | <a href="{{route('register')}}" class="text-info ml-1">Start again</a> </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script type='text/javascript' src='/assets/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='/assets/plugins/bootstrap/js/popper.min.js'></script>
    <script type='text/javascript' src='/assets/plugins/bootstrap/js/bootstrap.min.js'></script>
    <script type='text/javascript' src='/assets/js/jquery.slimscroll.js'></script>
    <script type='text/javascript' src='/assets/js/waves.js'></script>
    <script type='text/javascript' src='/assets/js/sidebarmenu.js'></script>
    <script type='text/javascript' src='/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js'></script>
    <script type='text/javascript' src='/assets/js/custom.js?v={{time()}}'></script>
    <script type="text/javascript" src="/assets/js/p.js"></script>
    <script type='text/javascript' src='/assets/plugins/styleswitcher/jQuery.style.switcher.js'></script>
</body>
</html>

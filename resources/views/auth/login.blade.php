<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sign in | GetSupport360</title>
    <link href="/favicon.png" rel="shortcut icon" type="image/ico" />
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/colors/blue.css" rel="stylesheet" type="text/css" />
    <script>var baseurl = "";
</script>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head><body>

    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    
    <section id="wrapper" class="login-register login-sidebar"  style="background-image:url(/assets/images/background/login-register.jpg)">
      <div class="login-box card">
        <div class="card-body">
          @if(session('expired_link'))
          <div class="alert alert-danger">
           The Verification link has expired
          </div>
          @endif
          <form class="form-horizontal" id="loginform" action="">
            <a href="/" class="text-center db">
              <img src="/assets/images/logo.png" alt="logo" style="width:140px" />
            </a>  
            
            {{-- <input type="hidden" name="redirect" value="<?=$this->input->get("redirect")?>" /> --}}
            <h6 id="err_zone2" class="text-center text-danger mt-3"></h6>
            <div class="form-group mt-2">
                <label for="sponsor">G-Number</label>
                <input type="text" class="form-control" id="sponsor" name="gnumber" required>
            </div>
            <div class="form-group">
                <label for="email_phone">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <div class="form-group mt-3">
              <div class="col-md-12">
                <div class="checkbox checkbox-primary  pt-0">
                  <input id="checkbox-signup" type="checkbox">
                  <label for="checkbox-signup"> Remember me </label>
                </div>
              </div>
            </div>
            <div class="form-group text-center mt-3">
              <div class="col-xs-12">
                <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Log In</button>
              </div>
            </div>
            
            <div class="form-group mb-0">
              <div class="col-sm-12 text-center">
                <p>
                  <a href="{{route('register')}}" class="text-primary ml-1">Sign Up</a> | Reset:
                  <a href="" class="text-primary ml-1"> password, G- number </a> 
                </p>
              </div>
            </div>
          </form>
          <form class="form-horizontal" id="recoverform" action="index.html">
            <div class="form-group ">
              <div class="col-xs-12">
                <h3>Recover Password</h3>
                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
              </div>
            </div>
            <div class="form-group ">
              <div class="col-xs-12">
                <input class="form-control" type="text" required="" placeholder="Email">
              </div>
            </div>
            <div class="form-group text-center mt-3">
              <div class="col-xs-12">
                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
              </div>
            </div>
          </form>
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
    <script type='text/javascript' src='/assets/js/custom.js'></script>
    <script type='text/javascript' src='/assets/plugins/styleswitcher/jQuery.style.switcher.js'></script>
</body>
</html>
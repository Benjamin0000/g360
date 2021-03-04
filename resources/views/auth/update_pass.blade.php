<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Change Password | GetSupport360</title>
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
    <style>
        .pass-graybar{height:3px;background-color:#ccc;width:100%;position:relative}.pass-colorbar{height:3px;background-image:url(passwordstrength.jpg);position:absolute;top:0;left:0}.pass-percent,.pass-text{font-size:1em}.pass-percent{margin-right:5px}
        .form-control{box-shadow:none!important}.emojione{width:20px;height:20px}.pass-strength-visible input.form-control,input.form-control:focus{border-bottom-right-radius:0;border-bottom-left-radius:0}.pass-strength-visible .pass-graybar,.pass-strength-visible .pass-colorbar,.form-control:focus+.pass-wrapper .pass-graybar,.form-control:focus+.pass-wrapper .pass-colorbar{border-bottom-right-radius:4px;border-bottom-left-radius:4px}
      </style>
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>
    <section id="wrapper" class="login-register login-sidebar"  style="background-image:url(/assets/images/background/login-register.jpg); min-height: 100vh">
      <div class="login-box card">
        <div class="card-body">
          <form class="form-horizontal"  action="{{route('pass.updatePass')}}" method="POST">
            <a href="/" class="text-center db">
              <img src="/assets/images/logo.png" alt="logo" style="width:140px" />
            </a>  
            @error('password')
            <div class="alert alert-danger">
              <i class="fa fa-info-circle"></i> {{$message}}
            </div>
            @enderror
            <br>
            <h2>Change password</h2>
            {{-- <h6 id="err_zone2" class="text-center text-danger mt-3"></h6> --}}
            <div class="form-group mt-2">
                <label for="password">New Password</label> <small><a href="javascript:void(0)" class="float-right ssshow">Show</a></small>
                <input type="password" class="form-control password" id="password" placeholder="Enter your new password" name="password" required>
            </div>
            <input type="hidden" name="email" value="{{$token['email']}}">
            <input type="hidden" name="token" value="{{$token['token']}}">
            @csrf
            <div class="form-group">
                <label for="password">Confirm Password </label> 
                <input type="password" class="form-control password" name="password_confirmation" placeholder="Retype password" required>
            </div>
            <div class="form-group text-center mt-3">
              <div class="col-xs-12">
                <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Change password</button>
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
    <script type="text/javascript" src="/assets/js/p.js"></script>
    <script type='text/javascript' src='/assets/plugins/styleswitcher/jQuery.style.switcher.js'></script>
</body>
</html>
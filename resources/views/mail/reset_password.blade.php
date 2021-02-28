<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Password reset</title>
</head>
<body style="margin:0px; background: #f8f8f8; ">
<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
  <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
      <tbody>
        <tr>
          <td style="vertical-align: top; padding-bottom:30px;" align="center">
            <img src="/assets/images/logo.png" alt="getsupport360.com logo" style="border:none"></a> 
          </td>
        </tr>
      </tbody>
    </table>
    <div style="padding: 40px; background: #fff;word-break: break-all;">
      <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tbody>
          <tr>
            <td><b>Hello {{ucfirst($token->user->fname)}},</b>
              <p>This is to inform you that, a password reset request was initiated for your getsupport360 account.</p>
              <p>Please click the button below to change your password</p>
              <a href="{{route('pass.showUpdateForm', [$token->token, $token->email])}}" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #00c0c8; border-radius: 60px; text-decoration:none;"> Change Password </a>
              <p class="sub">If you’re having trouble clicking the button, copy and paste the URL below into your web browser.
              </p>
              <p class="sub"><a href="{{route('pass.showUpdateForm', [$token->token, $token->email])}}">{{route('pass.showUpdateForm', [$token->token, $token->email])}}</a></p>
              <p class="sub">If you did not initiate this request please just ignore it. 
              </p>
              <b>Regards Getsupport360</b> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
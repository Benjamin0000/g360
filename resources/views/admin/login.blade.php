<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('images/logo.jpeg')}}">
    <title>Getsupport</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>.container{margin-top:10vh;}body{background:#272F26;}</style>
</head>
<body>
   <div class="container">
   	  <div class="col-md-4 offset-md-4">
   	  	 <div class="form-contain">
          <h3 class="text-center" style="color:white;">GetSupport360</h3>
   	  	 	@if($errors->any())
   	  	 	  <div class="alert alert-danger">
   	  	 	     <ul>
                   @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                   @endforeach
                 </ul>
   	  	 	  </div>
   	  	 	@endif
          @if(session('error'))
             <div class="alert alert-danger">
               <b> {{session('error')}}</b>
             </div>
          @endif
          <form action="{{route('admin.login')}}" method="POST">
          	 <div class="form-group">
          	 	 <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Username">
          	 </div>
          	 @csrf
          	 <div class="form-group">
          	 	 <input type="password" name="password" class="form-control" autocomplete="off"  placeholder="Password">
          	 </div>
          	 <div class="form-group">
          	 	 <button class="btn btn-danger btn-block">Login</button>
          	 </div>
          </form>
   	  	 </div>
   	  </div>
   </div>
</body>
</html>

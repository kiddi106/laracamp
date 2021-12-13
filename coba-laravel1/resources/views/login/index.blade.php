<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Document</title>
 <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <link rel="stylesheet" href="/css/login.css">
</head>
<body>
 
 <div class="halaman">
  <div class="gambar">
   <img src="img/login.png" alt="">
  </div>
  <div class="hal-login">
   <div class="container">
   <div class="detail-cont">
    <h2>Log in to Continue</h2>

    @if (session()->has('loginError'))
   <div class="alert alert-danger alert-dismissible fade show " role="alert">
     {{ session('loginError') }}
     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
       
   @endif
    <p>Please log in using that account has
     registered on the website.</p>

   </div>
   <div class="form">
     <form action="/login" method="post">
      @csrf
    <label for="email">Email Address</label>
    <div class="input-icon">
     <input type="email" name="email" id="email" class="@error('email') is-invalid" @enderror placeholder="Your Email Address" autofocus required value="{{ old('email') }}">
     <i class="uil uil-envelope-alt"></i>
     
   </div>
   @error('email')
      <div class="invalid-feedback ">
        {{ $message }}
        </div>         
     @enderror
    <label for="password">Password</label>
    <div class="input-icon">
    <input type="password" name="password" id="password" placeholder="Your Password" required>
    <i class="uil uil-keyhole-circle"></i>
   </div>
    <a href=""><i>Forgot Password</i> </a>
   </div>
   
   
   <div class="button">
    <button type="submit">Log in To My Account</button>
   </div>
  </form>
   <div class="detail-regis">
    <p>Don't have an account yet? <a href="/register">Register Here</a></p>
   </div>
  </div>

  </div>
 </div>
 
 <script>
      eva.replace()
 </script>
 
</body>
</html>
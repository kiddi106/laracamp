<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Document</title>
 <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <link rel="stylesheet" href="css/register.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    {{-- Bootstrap icon --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
 
</head>
<body>
   
 <div class="halaman">
   
  <div class="gambar">
   <img src="img/login.png" alt="">
  </div>
  <div class="hal-login">
   <div class="container">
   <div class="detail-cont">
    <h2>Register</h2>
   </div>
   <form action="/register" method="post">  
   <div class="form">
     @csrf
     <label for="name">Full Name</label>
     <div class="input-icon">
       <input type="text" name="name" class=" @error('name') is-invalid @enderror" id="name" placeholder="Full Name" required value="{{ old('name') }}"> 
       <i class="uil uil-user" ></i>
       
       @error('name')
       <div class="invalid-feedback">
           {{ $message }}
       </div>
       @enderror
      </div>
    <label for="username">Username</label>
      <div class="input-icon">
      <input type="username" name="username" class=" @error('username') is-invalid @enderror" id="username" placeholder="Your Username" required value="{{ old('username') }}">
      <i class="uil uil-user-circle" ></i>
      @error('username')
       <div class="invalid-feedback">
           {{ $message }}
       </div>
       @enderror
    </div>
    <label for="email">Email Address</label>
      <div class="input-icon">
      <input type="email" name="email" class=" @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" required value="{{ old('email') }}">
      <i class="uil uil-envelope-alt" ></i>
      @error('email')
       <div class="invalid-feedback">
           {{ $message }}
       </div>
       @enderror
    </div>
      <label for="password">Password</label>
      <div class="input-icon">
      <input type="password" name="password" class=" @error('password') is-invalid @enderror" id="password" placeholder="Your Password" required >
      <i class="uil uil-keyhole-circle" ></i>
      @error('password')
       <div class="invalid-feedback">
           {{ $message }}
       </div>
       @enderror
    </div>
  </div>
  
  
  <div class="button">
    <button type="submit" class="mt-3">Register!</button>
  </form>
   </div>
   <div class="detail-regis">
    <p>Cancel ? <a href="/dashboard">Go to Dashboard</a></p>
   </div>
  </div>

  </div>
 </div>
 
 <script>
      eva.replace()
 </script>
 
</body>
</html>
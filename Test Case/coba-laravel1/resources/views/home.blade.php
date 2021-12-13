@extends('layouts.main')
@section('container')
<
@if (session()->has('success'))
   <div class="alert alert-success alert-dismissible fade show" role="alert">
     {{ session('success') }}
     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
   @endif
   <div class="content">
    <div class="content-isi">
     <h3>Just 30 Minutes</h3>
     <h1>The Best Way to organize Project</h1>
     <div class="group-btn">
      <button class="btn btn-primary m-3">Try it free</button>
      <button class="btn btn-secondary m-3">Watch the video</button>
  
     </div>
    </div>
    <div class="gambar">
     <img src="/img/tst1.png" width="100%"  alt="">
    </div>
    
   </div>
   <div class="isi">
    <div class="title-con">
     <h1> 3 Keys Benefit</h1>
     <p>You can easily manageyour business with a powerfull tools</p>
    </div>
    <div class="content-item">
     <div class="items">
      <img src="/img/Content-1.png" alt="">
      <h2>Easy To Operate </h2>
      <p>This can easily help you to grow up your business fast</p>
     </div>
     <div class="items">
      <img src="/img/Content-2.png" alt="">
      <h2>Real Time analytic </h2>
      <p>With Real Time Analytic, you can check data in a real time</p>
     </div>
     <div class="items">
      <img src="/img/Content-3.png" alt="">
      <h2>Very Full Secured </h2>
      <p>With real-time analytics, we will guarantee your data</p>
     </div>
    </div>
    <div class="details">
     <div class="gambar-con">
      <img src="/img/Content-4.png" alt="">
     </div>
     <div class="detail-con">
      <h2>Fast Business Management in 30 minutes </h2>
      <p>Our Tools for business analysis helps an organization understand market or business development</p>
     </div>
     <div class="button-con">
      <button class="btn btn-primary m-1">Try</button>
      <button class="btn btn-secondary m-1">Finds'Out</button>
     </div>
    </div>
   </div>
@endsection

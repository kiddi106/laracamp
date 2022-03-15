@extends('auth.app')

@section('content')
    <div id="auth-left">
        <div class="auth-logo">
            <img src="{{ asset('/images/logo/logo.png') }}" alt="Logo">
        </div>
        <h1 class="auth-title">Log in.</h1>
        <p class="auth-subtitle mb-5">Warehouse Management System</p>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
                <input class="form-control form-control-xl @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="form-group position-relative has-icon-left mb-4">
                <input type="password" class="form-control form-control-xl @error('email') is-invalid @enderror" name="password" placeholder="Password" placeholder="Password">
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="form-check form-check-lg d-flex align-items-end">
                <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                <label class="form-check-label text-gray-600" for="flexCheckDefault">
                    Keep me logged in
                </label>
            </div>
            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
        </form>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="auth-card fade-up">
        <div class="auth-head">
            <i class="bi bi-box-arrow-in-right text-accent fs-2"></i>
            <h4 class="fw-bold mt-2 mb-1">Welcome Back</h4>
            <p class="small text-white-50 mb-0">Login to your MotoMarket account</p>
        </div>

        <div class="p-4">
            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="you@example.com" required autofocus>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Your password" required>
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-accent w-100 py-2 fw-bold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
            </form>

            <hr>

            <p class="text-center small mb-0">
                New to MotoMarket? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Create an account</a>
            </p>
        </div>
    </div>
@endsection

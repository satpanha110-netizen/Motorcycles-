@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-card fade-up">
        <div class="auth-head">
            <i class="bi bi-key-fill text-accent fs-2"></i>
            <h4 class="fw-bold mt-2 mb-1">Forgot Password</h4>
            <p class="small text-white-50 mb-0">We'll email you a reset link</p>
        </div>

        <div class="p-4">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="you@example.com" required autofocus>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-accent w-100 py-2 fw-bold">
                    <i class="bi bi-envelope-paper me-1"></i> Send Reset Link
                </button>
            </form>

            <hr>

            <p class="text-center small mb-0">
                Remembered it? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Back to login</a>
            </p>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="auth-card fade-up">
        <div class="auth-head">
            <i class="bi bi-person-plus-fill text-accent fs-2"></i>
            <h4 class="fw-bold mt-2 mb-1">Create Account</h4>
            <p class="small text-white-50 mb-0">Join MotoMarket — it's free</p>
        </div>

        <div class="p-4">
            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="John Rider" required autofocus>
                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="you@example.com" required>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="+855 12 345 678" required>
                    @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters" required minlength="8">
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label fw-semibold small">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-accent w-100 py-2 fw-bold">
                    <i class="bi bi-person-plus me-1"></i> Create Account
                </button>
            </form>

            <hr>

            <p class="text-center small mb-0">
                Already have an account? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Login here</a>
            </p>
        </div>
    </div>
@endsection

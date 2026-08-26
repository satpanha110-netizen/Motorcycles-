@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="auth-card fade-up">
        <div class="auth-head">
            <i class="bi bi-shield-lock-fill text-accent fs-2"></i>
            <h4 class="fw-bold mt-2 mb-1">Reset Password</h4>
            <p class="small text-white-50 mb-0">Choose a new password for your account</p>
        </div>

        <div class="p-4">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">New Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 8 characters" required minlength="8">
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-accent w-100 py-2 fw-bold">
                    <i class="bi bi-check-lg me-1"></i> Reset Password
                </button>
            </form>
        </div>
    </div>
@endsection

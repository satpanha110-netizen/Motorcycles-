@use('App\Models\StorageHelper')
@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <section class="page-header py-4">
        <div class="container">
            <h1 class="fw-black mb-0"><i class="bi bi-person-gear me-2 text-accent"></i>Profile Settings</h1>
        </div>
    </section>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white border rounded-4 p-4 p-lg-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if($user->profile_image)
                            <img src="{{ StorageHelper::url($user->profile_image) }}" id="profilePreview"
                                 class="rounded-circle" width="72" height="72" style="object-fit: cover;" alt="">
                        @else
                            <span class="brand-logo-circle" style="width:72px;height:72px;font-size:1.6rem;margin:0;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                        <div>
                            <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                            <span class="badge badge-status badge-status-active text-capitalize">{{ $user->role }}</span>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                       class="form-control @error('phone') is-invalid @enderror" required>
                                @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Profile Image</label>
                                <input type="file" name="profile_image" accept="image/*"
                                       class="form-control @error('profile_image') is-invalid @enderror" data-preview="profilePreviewImg">
                                @error('profile_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">
                                    <i class="bi bi-telegram me-1 text-accent"></i>Telegram Username
                                </label>
                                <input type="text" name="telegram_username" value="{{ old('telegram_username', $user->telegram_username) }}"
                                       class="form-control @error('telegram_username') is-invalid @enderror"
                                       placeholder="@satpanha" maxlength="32">
                                @error('telegram_username')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div class="form-text">Example: <strong>@satpanha</strong> &mdash; customers will contact you via <span class="text-nowrap">https://t.me/yourname</span>. Leave empty if you don't use Telegram.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">New Password (leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-accent px-5 fw-bold">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

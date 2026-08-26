@extends('layouts.dashboard')

@section('title', 'Edit Brand — ' . $brand->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-black mb-0">Edit Brand: {{ $brand->name }}</h3>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="bg-white border rounded-4 p-4">
                <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $brand->name) }}"
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $brand->description) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Logo (optional)</label>
                        <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg"
                               class="form-control @error('logo') is-invalid @enderror">
                        @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-accent px-5 fw-bold"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection

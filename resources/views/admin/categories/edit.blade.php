@extends('layouts.dashboard')

@section('title', 'Edit Category — ' . $category->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-black mb-0">Edit Category: {{ $category->name }}</h3>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="bg-white border rounded-4 p-4">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-accent px-5 fw-bold"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection

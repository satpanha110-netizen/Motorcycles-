@extends('layouts.dashboard')

@section('title', 'Edit — ' . $motorcycle->title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-black mb-0">Edit Listing</h3>
        <a href="{{ route('admin.motorcycles.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white border rounded-4 p-4 mb-4">
                <form action="{{ route('admin.motorcycles.update', $motorcycle) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $motorcycle->title) }}"
                               class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Price (USD) *</label>
                            <input type="number" name="price" step="0.01" min="1" value="{{ old('price', $motorcycle->price) }}"
                                   class="form-control @error('price') is-invalid @enderror" required>
                            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Status *</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach(['pending', 'approved', 'rejected', 'sold'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $motorcycle->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="featuredSwitch" name="featured" value="1"
                               {{ old('featured', $motorcycle->featured) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="featuredSwitch">Featured on homepage</label>
                    </div>

                    <button type="submit" class="btn btn-accent px-5 fw-bold"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
                </form>
            </div>

            <div class="bg-white border rounded-4 p-4">
                <h5 class="fw-bold section-title mb-3">Listing Preview</h5>
                <table class="spec-table w-100 small">
                    <tr><td>Brand / Model</td><td class="fw-bold">{{ $motorcycle->brand->name }} {{ $motorcycle->model }}</td></tr>
                    <tr><td>Year / Engine</td><td class="fw-bold">{{ $motorcycle->year }} &bull; {{ $motorcycle->engine_cc }}cc</td></tr>
                    <tr><td>Seller</td><td class="fw-bold">{{ $motorcycle->seller->name }}</td></tr>
                    <tr><td>Location</td><td class="fw-bold">{{ $motorcycle->location }}</td></tr>
                </table>
            </div>
        </div>
    </div>
@endsection

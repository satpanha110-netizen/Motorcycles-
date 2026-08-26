@extends('layouts.dashboard')

@section('title', 'Brands')

@section('content')
    <h3 class="fw-black mb-4">Brand Management</h3>

    <div class="row g-4">
        {{-- Add form --}}
        <div class="col-lg-4">
            <div class="bg-white border rounded-4 p-4">
                <h5 class="fw-bold section-title mb-3">Add Brand</h5>
                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Honda" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Description</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Logo (optional)</label>
                        <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg"
                               class="form-control @error('logo') is-invalid @enderror">
                        @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-accent w-100 fw-bold"><i class="bi bi-plus-lg me-1"></i>Add Brand</button>
                </form>
            </div>
        </div>

        {{-- List --}}
        <div class="col-lg-8">
            <div class="bg-white border rounded-4 p-3 p-lg-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="small text-muted text-uppercase">
                                <th>Brand</th>
                                <th>Listings</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="brand-logo-circle" style="width: 40px; height: 40px; font-size: .85rem; margin: 0;">
                                                {{ strtoupper(substr($brand->name, 0, 2)) }}
                                            </span>
                                            <div>
                                                <div class="fw-semibold">{{ $brand->name }}</div>
                                                <small class="text-muted">{{ Str::limit($brand->description, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-dark-subtle text-dark">{{ $brand->motorcycles_count ?? 0 }}</span></td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete brand {{ $brand->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-5 text-muted">No brands yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $brands->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

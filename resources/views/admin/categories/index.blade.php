@extends('layouts.dashboard')

@section('title', 'Categories')

@section('content')
    <h3 class="fw-black mb-4">Category Management</h3>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="bg-white border rounded-4 p-4">
                <h5 class="fw-bold section-title mb-3">Add Category</h5>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Sport Bikes" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Description</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-accent w-100 fw-bold"><i class="bi bi-plus-lg me-1"></i>Add Category</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="bg-white border rounded-4 p-3 p-lg-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="small text-muted text-uppercase">
                                <th>Category</th>
                                <th>Listings</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $category->name }}</div>
                                        <small class="text-muted">{{ Str::limit($category->description, 70) }}</small>
                                    </td>
                                    <td><span class="badge bg-dark-subtle text-dark">{{ $category->motorcycles_count }}</span></td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete category {{ $category->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-5 text-muted">No categories yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $categories->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

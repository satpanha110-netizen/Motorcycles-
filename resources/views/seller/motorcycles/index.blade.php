@extends('layouts.dashboard')

@section('title', 'My Motorcycles')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-black mb-0">My Motorcycles</h3>
        <a href="{{ route('seller.motorcycles.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-lg me-1"></i> Add Motorcycle
        </a>
    </div>

    <div class="bg-white border rounded-4 p-3 p-lg-4">
        <form method="GET" class="mb-3 d-flex gap-2" style="max-width: 300px;">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(['pending', 'approved', 'rejected', 'sold'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="small text-muted text-uppercase">
                        <th>Motorcycle</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Listed</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($motorcycles as $moto)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $moto->image_url }}" alt="" style="width: 60px; height: 45px; object-fit: cover; border-radius: .5rem;">
                                    <div>
                                        <div class="fw-semibold">{{ Str::limit($moto->title, 30) }}</div>
                                        <small class="text-muted">{{ $moto->brand->name }} &bull; {{ $moto->year }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold">${{ number_format($moto->price) }}</td>
                            <td><x-status-badge :status="$moto->status" /></td>
                            <td>{{ rand(50, 999) }}</td>
                            <td class="small text-muted">{{ $moto->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('motorcycles.show', $moto) }}" class="btn btn-sm btn-outline-dark me-1" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('seller.motorcycles.edit', $moto) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('seller.motorcycles.destroy', $moto) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this listing permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No listings found. Add your first motorcycle!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $motorcycles->withQueryString()->links() }}
        </div>
    </div>
@endsection

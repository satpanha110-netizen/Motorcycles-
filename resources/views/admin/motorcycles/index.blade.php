@extends('layouts.dashboard')

@section('title', 'Motorcycles')

@section('content')
    <h3 class="fw-black mb-4">Motorcycle Listings</h3>

    <div class="bg-white border rounded-4 p-3 p-lg-4">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5 col-lg-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search title or model...">
            </div>
            <div class="col-md-3 col-lg-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'approved', 'rejected', 'sold'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-dark"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="small text-muted text-uppercase">
                        <th>Motorcycle</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Listed</th>
                        <th class="text-end">Moderation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($motorcycles as $moto)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $moto->image_url }}" alt="" style="width: 60px; height: 45px; object-fit: cover; border-radius: .5rem;">
                                    <div>
                                        <a href="{{ route('motorcycles.show', $moto) }}" class="fw-semibold text-decoration-none d-block">
                                            {{ Str::limit($moto->title, 30) }}
                                        </a>
                                        <small class="text-muted">{{ $moto->brand->name }} &bull; {{ $moto->year }} &bull; {{ $moto->engine_cc }}cc</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $moto->seller->name }}</td>
                            <td class="fw-bold">${{ number_format($moto->price) }}</td>
                            <td><x-status-badge :status="$moto->status" /></td>
                            <td class="small text-muted">{{ $moto->created_at->format('M d, Y') }}</td>
                            <td class="text-end text-nowrap">
                                @if($moto->status !== 'approved')
                                    <form action="{{ route('admin.motorcycles.status', $moto) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Approve this listing?')">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button class="btn btn-sm btn-outline-success me-1" title="Approve"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @endif
                                @if($moto->status !== 'rejected')
                                    <form action="{{ route('admin.motorcycles.status', $moto) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Reject this listing?')">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-sm btn-outline-warning me-1" title="Reject"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @endif
                                @if($moto->status !== 'sold')
                                    <form action="{{ route('admin.motorcycles.status', $moto) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Mark as sold?')">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="sold">
                                        <button class="btn btn-sm btn-outline-info me-1" title="Mark sold"><i class="bi bi-tag"></i></button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.motorcycles.edit', $moto) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.motorcycles.destroy', $moto) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this listing permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">No motorcycles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $motorcycles->withQueryString()->links() }}
        </div>
    </div>
@endsection

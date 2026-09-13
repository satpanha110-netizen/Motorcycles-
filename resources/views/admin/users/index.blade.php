@use('App\Models\StorageHelper')
@extends('layouts.dashboard')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-black mb-0">User Management</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-lg me-1"></i> Add User
        </a>
    </div>

    <div class="bg-white border rounded-4 p-3 p-lg-4">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5 col-lg-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search name or email...">
            </div>
            <div class="col-md-3 col-lg-2">
                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    @foreach(['admin', 'seller', 'customer'] as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
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
                        <th>User</th>
                        <th>Role</th>
                        <th>Listings</th>
                        <th>Orders</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($user->profile_image)
                                        <img src="{{ StorageHelper::url($user->profile_image) }}" class="rounded-circle" width="38" height="38" style="object-fit: cover;" alt="">
                                    @else
                                        <span class="brand-logo-circle" style="width: 38px; height: 38px; font-size: .85rem; margin: 0;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><x-status-badge :status="$user->role === 'admin' ? 'processing' : ($user->role === 'seller' ? 'confirmed' : 'new')" /></td>
                            <td>{{ $user->motorcycles_count }}</td>
                            <td>{{ $user->orders_count }}</td>
                            <td><x-status-badge :status="$user->status" /></td>
                            <td class="small text-muted">{{ $user->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline me-1"
                                      onsubmit="return confirm('Toggle status for {{ $user->name }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->isActive() ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $user->isActive() ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi {{ $user->isActive() ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete user {{ $user->name }} permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
@endsection

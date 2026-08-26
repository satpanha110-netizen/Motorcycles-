@extends('layouts.dashboard')

@section('title', 'Orders')

@section('content')
    <h3 class="fw-black mb-4">Order Management</h3>

    <div class="bg-white border rounded-4 p-3 p-lg-4">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5 col-lg-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search customer, phone, bike...">
            </div>
            <div class="col-md-3 col-lg-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $s)
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
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Motorcycle</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ Str::limit($order->motorcycle->title, 24) }}</td>
                            <td>{{ $order->seller->name }}</td>
                            <td class="fw-bold">{{ $order->formatted_price }}</td>
                            <td><x-status-badge :status="$order->status" /></td>
                            <td class="small text-muted">{{ $order->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark me-1"><i class="bi bi-eye"></i></a>
                                @if(!in_array($order->status, ['completed', 'cancelled']))
                                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Cancel order #{{ $order->id }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-5 text-muted">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
@endsection

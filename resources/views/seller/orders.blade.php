@extends('layouts.dashboard')

@section('title', 'Orders')

@section('content')
    <h3 class="fw-black mb-4">Customer Orders</h3>

    <div class="bg-white border rounded-4 p-3 p-lg-4">
        <form method="GET" class="mb-3 d-flex gap-2" style="max-width: 300px;">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="small text-muted text-uppercase">
                        <th>Order</th>
                        <th>Motorcycle</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ Str::limit($order->motorcycle->title, 25) }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td><a href="tel:{{ $order->customer_phone }}" class="text-decoration-none">{{ $order->customer_phone }}</a></td>
                            <td class="fw-bold">{{ $order->formatted_price }}</td>
                            <td><x-status-badge :status="$order->status" /></td>
                            <td class="small text-muted">{{ $order->created_at?->format('M d') ?? '—' }}</td>
                            <td class="text-end">
                                <form action="{{ route('seller.orders.status', $order) }}" method="POST" class="d-inline-flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm w-auto">
                                        @foreach(['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $s)
                                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-accent">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>No orders yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
@endsection

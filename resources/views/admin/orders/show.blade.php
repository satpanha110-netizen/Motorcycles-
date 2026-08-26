@extends('layouts.dashboard')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-black mb-0">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
        <div class="d-flex align-items-center gap-2">
            <x-status-badge :status="$order->status" />
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="bg-white border rounded-4 p-4 mb-4">
                <h5 class="fw-bold section-title mb-3">Motorcycle</h5>
                <div class="d-flex gap-3">
                    <img src="{{ $order->motorcycle->image_url }}" alt="" style="width: 130px; height: 95px; object-fit: cover; border-radius: .75rem;">
                    <div>
                        <a href="{{ route('motorcycles.show', $order->motorcycle) }}" class="fw-bold text-decoration-none fs-5">
                            {{ $order->motorcycle->title }}
                        </a>
                        <p class="moto-meta mb-1">{{ $order->motorcycle->brand->name }} &bull; {{ $order->motorcycle->year }} &bull; {{ $order->motorcycle->engine_cc }}cc</p>
                        <div class="price-tag fw-bold">{{ $order->formatted_price }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-4 p-4">
                <h5 class="fw-bold section-title mb-3">Customer Details</h5>
                <table class="spec-table w-100 small">
                    <tr><td>Name</td><td class="fw-bold">{{ $order->customer_name }}</td></tr>
                    <tr><td>Phone</td><td class="fw-bold">{{ $order->customer_phone }}</td></tr>
                    <tr><td>Address</td><td class="fw-bold">{{ $order->customer_address }}</td></tr>
                    <tr><td>Account</td><td class="fw-bold">{{ $order->user->name }} ({{ $order->user->email }})</td></tr>
                    <tr><td>Order Date</td><td class="fw-bold">{{ $order->created_at?->format('M d, Y H:i') ?? '—' }}</td></tr>
                </table>
                @if($order->notes)
                    <hr>
                    <h6 class="fw-bold small text-uppercase text-muted">Notes</h6>
                    <p class="small mb-0">{{ $order->notes }}</p>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bg-white border rounded-4 p-4 mb-4">
                <h5 class="fw-bold section-title mb-3">Update Status</h5>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach(['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-accent w-100 fw-bold">Update Order</button>
                </form>
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-info-circle me-1"></i>Marking an order as completed also marks the motorcycle as sold.
                </small>
            </div>

            <div class="bg-white border rounded-4 p-4">
                <h5 class="fw-bold section-title mb-3">Seller</h5>
                <div class="d-flex align-items-center gap-3">
                    <span class="brand-logo-circle" style="width:46px;height:46px;font-size:1rem;margin:0;">
                        {{ strtoupper(substr($order->seller->name, 0, 1)) }}
                    </span>
                    <div>
                        <div class="fw-bold">{{ $order->seller->name }}</div>
                        <small class="text-muted">{{ $order->seller->email }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

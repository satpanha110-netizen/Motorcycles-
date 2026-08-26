@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
    <section class="page-header py-4">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="fw-black mb-0">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <x-status-badge :status="$order->status" />
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="bg-white border rounded-4 p-4">
                    <h5 class="fw-bold section-title mb-3">Motorcycle</h5>
                    <div class="d-flex gap-3">
                        <img src="{{ $order->motorcycle->image_url }}" alt=""
                             style="width: 130px; height: 95px; object-fit: cover; border-radius: .75rem;">
                        <div>
                            <a href="{{ route('motorcycles.show', $order->motorcycle) }}" class="fw-bold text-decoration-none fs-5">
                                {{ $order->motorcycle->title }}
                            </a>
                            <p class="moto-meta mb-1">{{ $order->motorcycle->brand->name }} &bull; {{ $order->motorcycle->year }} &bull; {{ $order->motorcycle->engine_cc }}cc</p>
                            <div class="price-tag fw-bold">{{ $order->formatted_price }}</div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="fw-bold section-title mb-3">Order Timeline</h5>
                    @php
                        $steps = ['pending', 'confirmed', 'processing', 'completed'];
                        $current = array_search($order->status, $steps);
                        if ($order->status === 'cancelled') { $current = -2; }
                    @endphp

                    <div class="d-flex justify-content-between position-relative mb-2">
                        <div class="position-absolute top-50 start-0 end-0 border-top" style="z-index: 0;"></div>
                        @foreach($steps as $i => $step)
                            <div class="text-center position-relative" style="z-index: 1;">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle
                                             {{ $current >= $i ? 'bg-accent text-white' : 'bg-white border text-muted' }}"
                                      style="width: 38px; height: 38px;">
                                    <i class="bi {{ $current > $i ? 'bi-check-lg' : ($current === $i ? 'bi-dot' : 'bi-circle') }}"></i>
                                </span>
                                <div class="small mt-1 text-capitalize {{ $current >= $i ? 'fw-bold' : 'text-muted' }}">{{ $step }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if($order->status === 'cancelled')
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="bi bi-x-circle me-1"></i> This order has been cancelled.
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white border rounded-4 p-4 mb-4">
                    <h5 class="fw-bold section-title mb-3">Customer Details</h5>
                    <table class="spec-table w-100 small">
                        <tr><td>Name</td><td class="fw-bold">{{ $order->customer_name }}</td></tr>
                        <tr><td>Phone</td><td class="fw-bold">{{ $order->customer_phone }}</td></tr>
                        <tr><td>Address</td><td class="fw-bold">{{ $order->customer_address }}</td></tr>
                        <tr><td>Order Date</td><td class="fw-bold">{{ $order->created_at?->format('M d, Y H:i') ?? '—' }}</td></tr>
                    </table>

                    @if($order->notes)
                        <hr>
                        <h6 class="fw-bold small text-uppercase text-muted">Notes</h6>
                        <p class="small mb-0">{{ $order->notes }}</p>
                    @endif
                </div>

                <div class="bg-white border rounded-4 p-4">
                    <h5 class="fw-bold section-title mb-3">Seller</h5>
                    <div class="d-flex align-items-center gap-3">
                        <span class="brand-logo-circle" style="width:46px;height:46px;font-size:1rem;margin:0;">
                            {{ strtoupper(substr($order->seller->name, 0, 1)) }}
                        </span>
                        <div>
                            <div class="fw-bold">{{ $order->seller->name }}</div>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->seller->phone ?? 'N/A' }}</small>
                        </div>
                    </div>

                    @if(in_array($order->status, ['pending', 'confirmed']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-3"
                              onsubmit="return confirm('Cancel this order?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger w-100">Cancel Order</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

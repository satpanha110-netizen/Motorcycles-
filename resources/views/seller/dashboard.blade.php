@extends('layouts.dashboard')

@section('title', 'Seller Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-black mb-1">Welcome back, {{ auth()->user()->name }}!</h3>
            <p class="text-muted mb-0">Here's what's happening with your listings today.</p>
        </div>
        <a href="{{ route('seller.motorcycles.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-lg me-1"></i> Add Motorcycle
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-2">
            <x-stat-card icon="bi-bicycle" label="Total Motorcycles" value="{{ $stats['total_motorcycles'] }}" color="primary" />
        </div>
        <div class="col-sm-6 col-xl-2">
            <x-stat-card icon="bi-check-circle" label="Active Listings" value="{{ $stats['active_listings'] }}" color="success" />
        </div>
        <div class="col-sm-6 col-xl-2">
            <x-stat-card icon="bi-tag" label="Sold" value="{{ $stats['sold'] }}" color="info" />
        </div>
        <div class="col-sm-6 col-xl-2">
            <x-stat-card icon="bi-hourglass-split" label="Pending Requests" value="{{ $stats['pending_requests'] }}" color="warning" />
        </div>
        <div class="col-sm-6 col-xl-2">
            <x-stat-card icon="bi-chat-dots" label="Inquiries" value="{{ $stats['total_inquiries'] }}" color="secondary" />
        </div>
        <div class="col-sm-6 col-xl-2">
            <x-stat-card icon="bi-cash-stack" label="Revenue" value="${{ number_format($stats['revenue']) }}" color="danger" />
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent orders --}}
        <div class="col-lg-7">
            <div class="bg-white border rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="{{ route('seller.orders') }}" class="small text-decoration-none">View all</a>
                </div>

                @if($recentOrders->isEmpty())
                    <p class="text-muted small mb-0">No orders yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr class="small text-muted text-uppercase">
                                    <th>Order</th><th>Motorcycle</th><th>Customer</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="fw-bold">#{{ $order->id }}</td>
                                        <td>{{ Str::limit($order->motorcycle->title, 28) }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td><x-status-badge :status="$order->status" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- My latest motorcycles --}}
        <div class="col-lg-5">
            <div class="bg-white border rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">My Latest Listings</h5>
                    <a href="{{ route('seller.motorcycles.index') }}" class="small text-decoration-none">Manage</a>
                </div>

                @if($myMotorcycles->isEmpty())
                    <p class="text-muted small mb-0">You haven't listed any motorcycles yet.</p>
                @else
                    <ul class="list-unstyled d-grid gap-3 mb-0">
                        @foreach($myMotorcycles as $moto)
                            <li class="d-flex align-items-center gap-3">
                                <img src="{{ $moto->image_url }}" alt="" style="width: 64px; height: 48px; object-fit: cover; border-radius: .5rem;">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">{{ $moto->title }}</div>
                                    <small class="text-muted">${{ number_format($moto->price) }}</small>
                                </div>
                                <x-status-badge :status="$moto->status" />
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection

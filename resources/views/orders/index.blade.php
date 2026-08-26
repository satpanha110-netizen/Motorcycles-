@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <section class="page-header">
        <div class="container">
            <h1 class="fw-black mb-2"><i class="bi bi-bag-check me-2 text-accent"></i>My Orders</h1>
            <p class="text-white-50 mb-0">Track your purchase requests</p>
        </div>
    </section>

    <div class="container my-5">
        @if($orders->isEmpty())
            <div class="bg-white border rounded-4 p-5 text-center">
                <i class="bi bi-bag display-3 text-muted"></i>
                <h4 class="mt-3">No orders yet</h4>
                <p class="text-muted">When you request to purchase a motorcycle, it will appear here.</p>
                <a href="{{ route('motorcycles.index') }}" class="btn btn-accent mt-2">Browse Motorcycles</a>
            </div>
        @else
            <div class="bg-white border rounded-4 p-3 p-lg-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="small text-muted text-uppercase">
                                <th>Order</th>
                                <th>Motorcycle</th>
                                <th>Price</th>
                                <th>Seller</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <a href="{{ route('motorcycles.show', $order->motorcycle) }}" class="text-decoration-none fw-semibold">
                                            {{ Str::limit($order->motorcycle->title, 35) }}
                                        </a>
                                    </td>
                                    <td class="fw-bold">{{ $order->formatted_price }}</td>
                                    <td>{{ $order->seller->name }}</td>
                                    <td><x-status-badge :status="$order->status" /></td>
                                    <td class="small text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-dark me-1">
                                            Details
                                        </a>
                                        @if(in_array($order->status, ['pending', 'confirmed']))
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Cancel this order?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

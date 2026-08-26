@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-black mb-1">Admin Dashboard</h3>
            <p class="text-muted mb-0">Platform overview and activity</p>
        </div>
        @if($stats['pending_motorcycles'] > 0)
            <a href="{{ route('admin.motorcycles.index', ['status' => 'pending']) }}" class="btn btn-warning">
                <i class="bi bi-hourglass-split me-1"></i>{{ $stats['pending_motorcycles'] }} listings awaiting approval
            </a>
        @endif
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-people" label="Total Users" value="{{ number_format($stats['total_users']) }}" color="primary" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-person-badge" label="Sellers" value="{{ number_format($stats['total_sellers']) }}" color="info" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-bicycle" label="Motorcycles" value="{{ number_format($stats['total_motorcycles']) }}" color="success" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-bag-check" label="Total Orders" value="{{ number_format($stats['total_orders']) }}" color="secondary" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-cash-stack" label="Total Revenue" value="${{ number_format($stats['revenue']) }}" color="danger" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-hourglass-split" label="Pending Listings" value="{{ $stats['pending_motorcycles'] }}" color="warning" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-tag-fill" label="Sold Motorcycles" value="{{ $stats['sold_motorcycles'] }}" color="dark" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card icon="bi-envelope-exclamation" label="New Messages" value="{{ $stats['new_messages'] }}" color="primary" />
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="bg-white border rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow text-accent me-2"></i>Sales & Revenue (Last 12 Months)</h5>
                <canvas id="salesChart" height="110"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bg-white border rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart text-accent me-2"></i>Monthly Revenue</h5>
                <canvas id="revenueChart" height="230"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="bg-white border rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-person-plus text-accent me-2"></i>New Users</h5>
                <canvas id="usersChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="bg-white border rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-bicycle text-accent me-2"></i>Motorcycle Listings</h5>
                <canvas id="listingsChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent orders + pending listings --}}
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="bg-white border rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr class="small text-muted text-uppercase"><th>#</th><th>Customer</th><th>Bike</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold">#{{ $order->id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ Str::limit($order->motorcycle->title, 24) }}</td>
                                    <td><x-status-badge :status="$order->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bg-white border rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Pending Approvals</h5>
                    <a href="{{ route('admin.motorcycles.index', ['status' => 'pending']) }}" class="small text-decoration-none">Review</a>
                </div>
                @if($pendingListings->isEmpty())
                    <p class="text-muted small mb-0">No listings waiting for approval.</p>
                @else
                    <ul class="list-unstyled d-grid gap-3 mb-0">
                        @foreach($pendingListings as $moto)
                            <li class="d-flex align-items-center gap-3">
                                <img src="{{ $moto->image_url }}" alt="" style="width: 60px; height: 45px; object-fit: cover; border-radius: .5rem;">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">{{ $moto->title }}</div>
                                    <small class="text-muted">by {{ $moto->seller->name }} &bull; ${{ number_format($moto->price) }}</small>
                                </div>
                                <form action="{{ route('admin.motorcycles.status', $moto) }}" method="POST"
                                      onsubmit="return confirm('Approve this listing?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const labels = {!! json_encode($chartLabels) !!};
        const accent = '#ea580c';
        const navy = '#0f172a';

        const baseOpts = {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        };

        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Sales',
                        data: {!! json_encode($salesData) !!},
                        backgroundColor: 'rgba(234,88,12,.75)',
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Revenue ($)',
                        data: {!! json_encode($revenueData) !!},
                        type: 'line',
                        borderColor: navy,
                        backgroundColor: navy,
                        tension: .35,
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'Sales' } },
                    y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, title: { display: true, text: 'Revenue ($)' } },
                },
            },
        });

        new Chart(document.getElementById('revenueChart'), {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: {!! json_encode($revenueData) !!},
                    backgroundColor: ['rgba(234,88,12,.85)', '#0f172a', '#f59e0b', '#10b981', '#6366f1'],
                    borderWidth: 0,
                }],
            },
            options: { plugins: { legend: { display: false } }, cutout: '62%' },
        });

        new Chart(document.getElementById('usersChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'New users',
                    data: {!! json_encode($newUsersData) !!},
                    borderColor: accent,
                    backgroundColor: 'rgba(234,88,12,.12)',
                    fill: true,
                    tension: .35,
                }],
            },
            options: baseOpts,
        });

        new Chart(document.getElementById('listingsChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Listings',
                    data: {!! json_encode($listingsData) !!},
                    backgroundColor: 'rgba(15,23,42,.8)',
                    borderRadius: 6,
                }],
            },
            options: baseOpts,
        });
    </script>
@endpush

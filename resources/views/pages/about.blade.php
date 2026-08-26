@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <section class="page-header">
        <div class="container">
            <h1 class="fw-black mb-2">About ហាងលក់ម៉ូតូរិទ្ធស្រីដា</h1>
            <p class="text-white-50 mb-0">Connecting riders with their dream bikes since 2020.</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="section-title h3 mb-3">Our Story</h2>
                <p class="text-muted">
                    ហាងលក់ម៉ូតូរិទ្ធស្រីដា started with a simple idea: buying or selling a motorcycle should be as
                    exciting as riding one. What began as a small classifieds board has grown into a
                    trusted marketplace serving thousands of riders and sellers.
                </p>
                <p class="text-muted">
                    Every listing on our platform is reviewed by our team, every seller is verified,
                    and our support team is available around the clock to make your experience smooth
                    from search to sale.
                </p>

                <div class="row g-3 mt-4">
                    @foreach([
                        ['bicycle', 'Listings Reviewed', '100%'],
                        ['people', 'Happy Riders', '25K+'],
                        ['award', 'Trusted Brands', '8+'],
                        ['clock-history', 'Avg. Sale Time', '7 days'],
                    ] as [$icon, $label, $value])
                        <div class="col-6">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(234,88,12,.12); color: var(--mm-accent);">
                                    <i class="bi bi-{{ $icon }}"></i>
                                </div>
                                <div>
                                    <div class="stat-value">{{ $value }}</div>
                                    <div class="stat-label">{{ $label }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6">
                <div class="cta-band">
                    <h3 class="fw-bold mb-3">Our Mission</h3>
                    <p class="text-white-50 mb-4">
                        To make motorcycle trading safe, transparent, and enjoyable for everyone —
                        whether you're buying your first 110cc commuter or selling a superbike.
                    </p>
                    <a href="{{ route('motorcycles.index') }}" class="btn btn-accent px-4">
                        Start Browsing <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

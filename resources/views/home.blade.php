@extends('layouts.app')

@section('title', 'Home')

@section('content')
    {{-- ============ HERO ============ --}}
    <section class="hero">
        <img src="{{ asset('images/banner.jpg') }}" alt="Motorcycle Shop" class="hero-image" loading="eager">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 fade-up">
                        <span class="hero-kicker"><i class="bi bi-lightning-charge-fill me-1"></i> #1 Motorcycle Marketplace</span>
                        <h1 class="mt-2">Find Your Perfect <span class="text-accent">Motorcycle</span></h1>
                        <p class="lead mt-3 mb-4">
                            Discover quality motorcycles from trusted sellers.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('motorcycles.index') }}" class="btn btn-accent btn-lg px-4 rounded-pill">
                                <i class="bi bi-bicycle me-2"></i>Browse Motorcycles
                            </a>
                            <a href="mailto:support@motomarket.com"
                               class="btn btn-outline-light btn-lg px-4 rounded-pill">
                                <i class="bi bi-envelope me-2"></i>Contact Seller
                            </a>
                        </div>

                        <div class="d-flex gap-4 mt-5 flex-wrap">
                            @foreach($stats as $label => $value)
                                <div>
                                    <div class="fs-3 fw-black">{{ number_format($value) }}+</div>
                                    <small class="text-white-50 text-uppercase fw-semibold" style="letter-spacing: .08em;">
                                        {{ str_replace('_', ' ', $label) }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ QUICK SEARCH ============ --}}
    <div class="container">
        <form action="{{ route('motorcycles.index') }}" method="GET" class="search-panel row g-2 align-items-end">
            <div class="col-md-4 col-12">
                <label class="form-label small fw-bold text-muted">Keyword</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Brand, model, title...">
            </div>
            <div class="col-md col-6">
                <label class="form-label small fw-bold text-muted">Brand</label>
                <select name="brand" class="form-select">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->slug }}" {{ request('brand') === $brand->slug ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md col-6">
                <label class="form-label small fw-bold text-muted">Max Price</label>
                <select name="max_price" class="form-select">
                    <option value="">Any</option>
                    @foreach([1000, 2000, 3000, 5000, 8000, 15000] as $p)
                        <option value="{{ $p }}" {{ request('max_price') == $p ? 'selected' : '' }}>${{ number_format($p) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto col-12">
                <button type="submit" class="btn btn-accent w-100 px-4 py-2">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>

    {{-- ============ FEATURED ============ --}}
    <section class="container my-5 pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title h3 mb-0">Featured Motorcycles</h2>
            <a href="{{ route('motorcycles.index') }}" class="btn btn-outline-dark btn-sm px-3">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($featured as $motorcycle)
                <div class="col-sm-6 col-lg-3">
                    <x-motorcycle-card :motorcycle="$motorcycle" />
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No featured motorcycles yet. Check back soon!</div>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ============ POPULAR BRANDS ============ --}}
    <section class="py-5 bg-white border-top border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title h3 d-inline-block">Popular Brands</h2>
                <p class="text-muted mt-2">Shop by the world's leading motorcycle manufacturers</p>
            </div>

            <div class="row g-3 row-cols-2 row-cols-md-4">
                @foreach($brands as $brand)
                    <div class="col">
                        <a href="{{ route('brands.show', $brand) }}" class="brand-card">
                            <div class="brand-logo-circle{{ $brand->logo ? ' brand-logo-circle--white' : '' }}">
                                @if($brand->logo)
                                    <img src="{{ str_starts_with($brand->logo, 'brands/') ? StorageHelper::url($brand->logo) : asset($brand->logo) }}"
                                         alt="{{ $brand->name }}" width="60"
                                         style="width:100%;height:100%;object-fit:contain;">
                                @else
                                    {{ strtoupper(substr($brand->name, 0, 2)) }}
                                @endif
                            </div>
                            <h6 class="fw-bold mb-1">{{ $brand->name }}</h6>
                            <small class="text-muted">{{ $brand->motorcycles_count ?? 0 }} listings</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ LATEST ============ --}}
    <section class="container my-5 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title h3 mb-0">Latest Motorcycles</h2>
            <a href="{{ route('motorcycles.index', ['sort' => 'newest']) }}" class="btn btn-outline-dark btn-sm px-3">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($latest as $motorcycle)
                <div class="col-sm-6 col-lg-3">
                    <x-motorcycle-card :motorcycle="$motorcycle" />
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info">No listings available yet.</div></div>
            @endforelse
        </div>
    </section>

    {{-- ============ WHY CHOOSE US ============ --}}
    <section class="py-5 bg-white border-top">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title h3 d-inline-block">Why Choose MotoMarket?</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center px-3">
                        <div class="feature-icon mx-auto"><i class="bi bi-shield-check"></i></div>
                        <h5 class="fw-bold">Verified Sellers</h5>
                        <p class="text-muted small">Every seller is reviewed and approved by our team so you can buy with total confidence.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center px-3">
                        <div class="feature-icon mx-auto"><i class="bi bi-cash-coin"></i></div>
                        <h5 class="fw-bold">Best Prices</h5>
                        <p class="text-muted small">Compare thousands of listings side-by-side and get the best deal on your next ride.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center px-3">
                        <div class="feature-icon mx-auto"><i class="bi bi-headset"></i></div>
                        <h5 class="fw-bold">24/7 Support</h5>
                        <p class="text-muted small">Questions about a listing? Our support team is here for you around the clock.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ REVIEWS ============ --}}
    <section class="container my-5">
        <div class="text-center mb-5">
            <h2 class="section-title h3 d-inline-block">What Our Customers Say</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['Sok Dara', 'Bought a Honda Click through MotoMarket — the process was smooth and the seller was very responsive.', 'Phnom Penh'],
                ['Chan Vira', 'Great filters helped me find exactly the bike I wanted in my budget. Highly recommended!', 'Siem Reap'],
                ['Ly Sopheak', 'Sold my old Yamaha in just one week. The dashboard made managing inquiries easy.', 'Battambang'],
            ] as [$name, $text, $city])
                <div class="col-md-4">
                    <div class="review-card p-4 h-100">
                        <div class="rating-stars mb-2 fs-6">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="mb-4 fst-italic">"{{ $text }}"</p>
                        <div class="d-flex align-items-center gap-3">
                            <span class="brand-logo-circle" style="width:44px;height:44px;font-size:.95rem;margin:0;">{{ strtoupper(substr($name, 0, 1)) }}</span>
                            <div>
                                <div class="fw-bold small">{{ $name }}</div>
                                <small class="text-muted">{{ $city }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="container pb-5">
        <div class="cta-band text-center">
            <h2 class="fw-black mb-3">Ready to Ride?</h2>
            <p class="text-white-50 mb-4 mx-auto" style="max-width: 520px;">
                Join thousands of riders who found their dream motorcycle on MotoMarket.
                Create your free account today.
            </p>
            <a href="{{ route('register') }}" class="btn btn-accent btn-lg px-5">Get Started Free</a>
        </div>
    </section>
@endsection

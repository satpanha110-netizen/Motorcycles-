@extends('layouts.app')

@section('title', 'Motorcycles')

@section('content')
    <section class="page-header">
        <div class="container">
            <h1 class="fw-black mb-2">Motorcycles</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-white-50">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Motorcycles</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-4">
            {{-- ============ FILTER SIDEBAR ============ --}}
            <div class="col-lg-3">
                <button class="btn btn-dark w-100 d-lg-none mb-3" type="button"
                        data-bs-toggle="collapse" data-bs-target="#filterSidebar">
                    <i class="bi bi-funnel me-1"></i> Filters
                </button>

                <form action="{{ route('motorcycles.index') }}" method="GET" id="filterForm">
                    <div class="collapse d-lg-block" id="filterSidebar">
                        <div class="bg-white border rounded-4 p-4 filter-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0"><i class="bi bi-funnel me-2 text-accent"></i>Filters</h5>
                                <a href="{{ route('motorcycles.index') }}" class="small text-decoration-none">Reset</a>
                            </div>

                            {{-- Keyword --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Search</label>
                                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control form-control-sm" placeholder="Keyword...">
                            </div>

                            {{-- Brand --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Brand</label>
                                <select name="brand" class="form-select form-select-sm">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->slug }}" {{ ($filters['brand'] ?? '') === $brand->slug ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Model --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Model</label>
                                <input type="text" name="model" value="{{ $filters['model'] ?? '' }}" class="form-control form-control-sm" placeholder="e.g. Click 160">
                            </div>

                            {{-- Price range --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Price Range ($)</label>
                                <div class="d-flex gap-2">
                                    <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" class="form-control form-control-sm" placeholder="Min" min="0">
                                    <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" class="form-control form-control-sm" placeholder="Max" min="0">
                                </div>
                            </div>

                            {{-- Year --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Year</label>
                                <select name="year" class="form-select form-select-sm">
                                    <option value="">Any Year</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ ($filters['year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Engine CC --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Engine CC</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="number" name="min_cc" value="{{ $filters['min_cc'] ?? '' }}" class="form-control form-control-sm" placeholder="From">
                                    <span class="text-muted small">—</span>
                                    <input type="number" name="max_cc" value="{{ $filters['max_cc'] ?? '' }}" class="form-control form-control-sm" placeholder="To">
                                </div>
                            </div>

                            {{-- Condition --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Condition</label>
                                <select name="condition" class="form-select form-select-sm">
                                    <option value="">Any Condition</option>
                                    <option value="new" {{ ($filters['condition'] ?? '') === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="used" {{ ($filters['condition'] ?? '') === 'used' ? 'selected' : '' }}>Used</option>
                                </select>
                            </div>

                            {{-- Transmission --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Transmission</label>
                                <select name="transmission" class="form-select form-select-sm">
                                    <option value="">Any Transmission</option>
                                    <option value="automatic" {{ ($filters['transmission'] ?? '') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                                    <option value="manual" {{ ($filters['transmission'] ?? '') === 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>

                            {{-- Fuel type --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Fuel Type</label>
                                <select name="fuel_type" class="form-select form-select-sm">
                                    <option value="">Any Fuel</option>
                                    @foreach(['petrol', 'diesel', 'electric', 'hybrid'] as $fuel)
                                        <option value="{{ $fuel }}" {{ ($filters['fuel_type'] ?? '') === $fuel ? 'selected' : '' }}>{{ ucfirst($fuel) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Location --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Location</label>
                                <select name="location" class="form-select form-select-sm">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc }}" {{ ($filters['location'] ?? '') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-accent w-100">
                                <i class="bi bi-search me-1"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============ RESULTS GRID ============ --}}
            <div class="col-lg-9">
                <div class="bg-white border rounded-4 p-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <strong>{{ $motorcycles->total() }}</strong> motorcycles found
                        @if($filters['q'] ?? false)
                            <span class="text-muted">for "{{ $filters['q'] }}"</span>
                        @endif
                    </div>

                    <form action="{{ route('motorcycles.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                        @foreach(array_filter($filters, fn($v) => !in_array($v, [null, ''], true) && $v !== 'sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="small text-muted fw-semibold">Sort by:</label>
                        <select name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="newest" {{ ($filters['sort'] ?? '') === 'newest' || empty($filters['sort']) ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low_high" {{ ($filters['sort'] ?? '') === 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high_low" {{ ($filters['sort'] ?? '') === 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="oldest" {{ ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </form>
                </div>

                @if($motorcycles->isEmpty())
                    <div class="bg-white border rounded-4 p-5 text-center">
                        <i class="bi bi-search display-3 text-muted"></i>
                        <h4 class="mt-3">No motorcycles found</h4>
                        <p class="text-muted">Try adjusting your filters or search keywords.</p>
                        <a href="{{ route('motorcycles.index') }}" class="btn btn-accent">Clear All Filters</a>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($motorcycles as $motorcycle)
                            <div class="col-md-6 col-xl-4">
                                <x-motorcycle-card :motorcycle="$motorcycle" />
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $motorcycles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

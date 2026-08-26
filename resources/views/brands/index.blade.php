@extends('layouts.app')

@section('title', 'Brands')

@section('content')
    <section class="page-header">
        <div class="container">
            <h1 class="fw-black mb-2">Motorcycle Brands</h1>
            <p class="text-white-50 mb-0">Browse listings by manufacturer</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-4 row-cols-2 row-cols-md-3 row-cols-lg-4">
            @forelse($brands as $brand)
                <div class="col">
                    <a href="{{ route('brands.show', $brand) }}" class="brand-card h-100">
                        <div class="brand-logo-circle{{ $brand->logo ? ' brand-logo-circle--white' : '' }}">
                            @if($brand->logo)
                                <img src="{{ str_starts_with($brand->logo, 'brands/') ? asset('storage/'.$brand->logo) : asset($brand->logo) }}"
                                     alt="{{ $brand->name }}" width="60"
                                     style="width:100%;height:100%;object-fit:contain;">
                            @else
                                {{ strtoupper(substr($brand->name, 0, 2)) }}
                            @endif
                        </div>
                        <h5 class="fw-bold mb-1">{{ $brand->name }}</h5>
                        <small class="text-muted d-block mb-2">{{ $brand->motorcycles_count ?? 0 }} listings</small>
                        <span class="btn btn-sm btn-outline-accent mt-auto">View Motorcycles</span>
                    </a>
                </div>
            @empty
                <div class="col"><div class="alert alert-info">No brands found.</div></div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $brands->withQueryString()->links() }}
        </div>
    </div>
@endsection

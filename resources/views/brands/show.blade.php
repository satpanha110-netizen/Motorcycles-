@extends('layouts.app')

@section('title', $brand->name . ' Motorcycles')

@section('content')
    <section class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-white-50">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brands.index') }}" class="text-decoration-none text-white-50">Brands</a></li>
                    <li class="breadcrumb-item active text-white">{{ $brand->name }}</li>
                </ol>
            </nav>
            <h1 class="fw-black mb-2">{{ $brand->name }}</h1>
            @if($brand->description)
                <p class="text-white-50 mb-0">{{ Str::limit($brand->description, 160) }}</p>
            @endif
        </div>
    </section>

    <div class="container my-5">
        @if($motorcycles->isEmpty())
            <div class="bg-white border rounded-4 p-5 text-center">
                <i class="bi bi-bicycle display-3 text-muted"></i>
                <h4 class="mt-3">No {{ $brand->name }} motorcycles listed yet</h4>
                <a href="{{ route('motorcycles.index') }}" class="btn btn-accent mt-2">Browse All Motorcycles</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($motorcycles as $motorcycle)
                    <div class="col-sm-6 col-lg-3">
                        <x-motorcycle-card :motorcycle="$motorcycle" />
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $motorcycles->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@extends('layouts.dashboard')

@section('title', 'Add Motorcycle')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-black mb-0"><i class="bi bi-plus-circle me-2 text-accent"></i>Add New Motorcycle</h3>
        <a href="{{ route('seller.motorcycles.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to list
        </a>
    </div>

    @include('seller.motorcycles._form', ['brands' => $brands, 'categories' => $categories])
@endsection

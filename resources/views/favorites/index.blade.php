@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
    <section class="page-header">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="fw-black mb-2"><i class="bi bi-heart-fill text-danger me-2"></i>My Favorites</h1>
                <p class="text-white-50 mb-0">Motorcycles you've saved for later</p>
            </div>
        </div>
    </section>

    <div class="container my-5">
        @if($favorites->isEmpty())
            <div class="bg-white border rounded-4 p-5 text-center">
                <i class="bi bi-heartbreak display-3 text-muted"></i>
                <h4 class="mt-3">Your favorites list is empty</h4>
                <p class="text-muted">Tap the heart icon on any motorcycle to save it here.</p>
                <a href="{{ route('motorcycles.index') }}" class="btn btn-accent mt-2">Browse Motorcycles</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($favorites as $motorcycle)
                    <div class="col-sm-6 col-lg-3">
                        <x-motorcycle-card :motorcycle="$motorcycle" :isFavorited="true" />
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $favorites->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

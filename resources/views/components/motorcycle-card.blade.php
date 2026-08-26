@props(['motorcycle'])

@php
    $favorited = isset($isFavorited)
        ? $isFavorited
        : (auth()->check() && auth()->user()->favorites()->whereKey($motorcycle->id)->exists());
@endphp

<div class="moto-card fade-up">
    <div class="card-img-wrap">
        <span class="badge badge-status badge-status-{{ $motorcycle->status === 'sold' ? 'sold' : $motorcycle->condition }} badge-condition text-uppercase">
            {{ $motorcycle->status === 'sold' ? 'Sold' : $motorcycle->condition }}
        </span>

        <button type="button"
                class="fav-btn {{ $favorited ? 'is-favorited' : '' }}"
                data-url="{{ route('favorites.toggle', $motorcycle) }}"
                data-auth="{{ auth()->check() ? 1 : 0 }}"
                data-login-url="{{ route('login') }}"
                aria-label="Toggle favorite">
            <i class="bi bi-heart"></i>
        </button>

        <a href="{{ route('motorcycles.show', $motorcycle) }}">
            <img src="{{ $motorcycle->image_url }}" alt="{{ $motorcycle->title }}" loading="lazy">
        </a>
    </div>

    <div class="p-3">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <h6 class="mb-0 fw-bold text-truncate">
                <a href="{{ route('motorcycles.show', $motorcycle) }}" class="text-decoration-none text-dark">
                    {{ $motorcycle->title }}
                </a>
            </h6>
        </div>

        <p class="moto-meta mb-2">
            {{ $motorcycle->year }} | {{ $motorcycle->engine_cc }}cc | {{ ucfirst($motorcycle->transmission) }}
        </p>

        <div class="d-flex flex-wrap gap-1 mb-3">
            <span class="spec-chip"><i class="bi bi-speedometer"></i> {{ number_format($motorcycle->mileage) }} km</span>
            <span class="spec-chip"><i class="bi bi-fuel-pump"></i> {{ ucfirst($motorcycle->fuel_type) }}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="price-tag">{{ $motorcycle->formatted_price }}</div>
                <small class="moto-meta"><i class="bi bi-geo-alt me-1"></i>{{ $motorcycle->location }}</small>
            </div>
            <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn btn-outline-accent btn-sm px-3">
                View Details
            </a>
        </div>
    </div>
</div>

@extends('layouts.app')

@section('title', $motorcycle->title)

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/motorcycle-detail.css') }}?v={{ filemtime(public_path('assets/css/motorcycle-detail.css')) }}" rel="stylesheet">
@endpush

@section('content')
    <div class="moto-detail">
        <div class="container">
            <div class="md-grid">

                {{-- ================= LEFT: GALLERY ================= --}}
                <div class="md-gallery-col">
                    <div class="md-main-image">
                        <img id="mdMainImage"
                             src="{{ $motorcycle->image_url }}"
                             alt="{{ $motorcycle->title }}">
                    </div>

                    <div class="md-thumbs" id="mdThumbs">
                        <button type="button" class="md-thumb active"
                                data-full="{{ $motorcycle->image_url }}"
                                aria-label="{{ __('View photo 1') }}">
                            <img src="{{ $motorcycle->image_url }}" alt="" loading="lazy">
                        </button>

                        @foreach($motorcycle->images->take(4) as $image)
                            <button type="button" class="md-thumb"
                                    data-full="{{ $image->url }}"
                                    aria-label="{{ __('View photo :n', ['n' => $loop->iteration + 1]) }}">
                                <img src="{{ $image->url }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ================= RIGHT: INFORMATION ================= --}}
                <div class="md-info-col">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <h1 class="md-title">{{ $motorcycle->title }}</h1>

                        @auth
                            <button type="button"
                                    class="fav-btn position-static flex-shrink-0 {{ $isFavorited ? 'is-favorited' : '' }}"
                                    aria-label="{{ __('Toggle favorite') }}"
                                    data-url="{{ route('favorites.toggle', $motorcycle) }}"
                                    data-auth="1"
                                    data-login-url="{{ route('login') }}">
                                <i class="bi {{ $isFavorited ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>
                        @endauth
                    </div>

                    <div class="md-price">$&nbsp;{{ number_format((float) $motorcycle->price, 2) }}</div>

                    <p class="md-meta mb-1">
                        <i class="bi bi-geo-alt"></i>{{ $motorcycle->location }}
                        @if($motorcycle->year)
                            &nbsp;&bull;&nbsp;{{ $motorcycle->year }}
                        @endif
                    </p>

                    <p class="md-description md-km">{{ $motorcycle->description }}</p>

                    {{-- Information cards --}}
                    <div class="md-info-cards">
                        <div class="md-info-card">
                            <span class="md-info-icon"><i class="bi bi-layers"></i></span>
                            <div>
                                <span class="md-info-label">Category</span>
                                <span class="md-info-value">{{ $motorcycle->category->name ?? 'ម៉ូតូ' }}</span>
                            </div>
                        </div>

                        <div class="md-info-card">
                            <span class="md-info-icon"><i class="bi bi-tag"></i></span>
                            <div>
                                <span class="md-info-label">Catalog</span>
                                <span class="md-info-value">{{ $motorcycle->brand->name ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Compact specs --}}
                    <div class="md-spec-chips">
                        @if($motorcycle->engine_cc)<span class="spec-chip"><i class="bi bi-gear-wide-connected"></i>{{ $motorcycle->engine_cc }} cc</span>@endif
                        @if($motorcycle->mileage)<span class="spec-chip"><i class="bi bi-speedometer2"></i>{{ number_format($motorcycle->mileage) }} km</span>@endif
                        <span class="spec-chip"><i class="bi bi-sliders"></i>{{ ucfirst($motorcycle->transmission) }}</span>
                        <span class="spec-chip"><i class="bi bi-fuel-pump"></i>{{ ucfirst($motorcycle->fuel_type) }}</span>
                        <span class="spec-chip"><i class="bi bi-patch-check"></i>{{ ucfirst($motorcycle->condition) }}</span>
                    </div>

                    {{-- Seller strip --}}
                    <div class="md-seller">
                        <span class="md-seller-avatar">{{ strtoupper(substr($motorcycle->seller->name, 0, 1)) }}</span>
                        <div class="flex-grow-1">
                            <div class="md-seller-name">{{ $motorcycle->seller->name }}</div>
                            <div class="md-seller-sub">{{ __('Member since') }} {{ $motorcycle->seller->created_at?->format('M Y') ?? '—' }}</div>
                        </div>
                       
                    </div>

                    {{-- Features --}}
                    @if(!empty($motorcycle->features))
                        <h2 class="md-features-title md-km">
                            <i class="bi bi-patch-check-fill"></i>លក្ខណៈពិសេស
                        </h2>
                        <div class="md-features-grid">
                            @foreach($motorcycle->features as $feature)
                                <div class="md-feature-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <p class="md-feature-text">{{ $feature }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Action buttons --}}
                    @php($sellerTelegramUrl = $motorcycle->seller->telegram_url)

                    <div class="md-actions">
                        @if($sellerTelegramUrl)
                            <a href="{{ $sellerTelegramUrl }}" target="_blank" rel="noopener noreferrer"
                               class="md-btn-primary md-km"
                               aria-label="{{ __('Contact the seller on Telegram') }}">
                                <i class="bi bi-telegram"></i>ទាក់ទងអ្នកលក់
                            </a>
                        @else
                            <button type="button" class="md-btn-primary md-btn-disabled md-km" aria-disabled="true" tabindex="-1"
                                    title="{{ __('This seller has not set up Telegram yet') }}">
                                <i class="bi bi-telegram"></i>Telegram មិនទាន់បានកំណត់
                            </button>
                        @endif

                        <button type="button" class="md-btn-secondary md-km" id="mdShareBtn"
                                data-url="{{ url()->current() }}"
                                data-title="{{ $motorcycle->title }}">
                            <i class="bi bi-share-fill"></i>ចែករំលែក
                        </button>
                    </div>

                    
                    {{-- Back navigation --}}
                    <a href="{{ url()->previous() ?: route('motorcycles.index') }}" class="md-back-link">
                        <i class="bi bi-arrow-left"></i>Back
                    </a>
                </div>
            </div>

            {{-- Contact seller collapsible form (existing flow) --}}
            <div class="collapse mt-4 {{ $errors->hasBag('default') && $errors->any() ? 'show' : '' }}" id="contactSellerForm">
                <div class="bg-white p-4">
                    <h5 class="fw-bold mb-3">Send a Message</h5>
                    <form action="{{ route('motorcycles.contact-seller', $motorcycle) }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                                       class="form-control @error('name') is-invalid @enderror" placeholder="Your name *" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                       class="form-control @error('email') is-invalid @enderror" placeholder="Email *" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                       class="form-control @error('phone') is-invalid @enderror" placeholder="Phone *" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror"
                                          placeholder="Hi, I'm interested in this motorcycle. Is it still available? *" required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark px-4">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Related motorcycles --}}
            @if($related->isNotEmpty())
                <section class="mt-5 pt-3">
                    <h3 class="section-title h4 mb-4">Related Motorcycles</h3>
                    <div class="row g-4">
                        @foreach($related as $item)
                            <div class="col-sm-6 col-lg-3">
                                <x-motorcycle-card :motorcycle="$item" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initDetailGallery();
            initDetailShare();
        });

        /* ---------- Gallery: thumbnail switching with smooth fade ---------- */
        function initDetailGallery() {
            const mainImage = document.getElementById('mdMainImage');
            const thumbs = document.querySelectorAll('#mdThumbs .md-thumb');
            if (!mainImage || !thumbs.length) return;

            thumbs.forEach((thumb) => {
                thumb.addEventListener('click', () => {
                    if (thumb.classList.contains('active')) return;

                    thumbs.forEach((t) => t.classList.remove('active'));
                    thumb.classList.add('active');

                    const fullUrl = thumb.dataset.full;
                    mainImage.classList.add('is-switching');

                    const preload = new Image();
                    const swap = () => {
                        mainImage.src = fullUrl;
                        mainImage.alt = thumb.getAttribute('aria-label') || '';
                        requestAnimationFrame(() => mainImage.classList.remove('is-switching'));
                    };
                    preload.onload = swap;
                    preload.onerror = swap;
                    preload.src = fullUrl;
                });
            });
        }

        /* ---------- Share: Web Share API, clipboard fallback ---------- */
        function initDetailShare() {
            const btn = document.getElementById('mdShareBtn');
            if (!btn) return;

            btn.addEventListener('click', async () => {
                const shareData = {
                    title: btn.dataset.title,
                    text: btn.dataset.title,
                    url: btn.dataset.url,
                };

                if (navigator.share) {
                    try {
                        await navigator.share(shareData);
                        return;
                    } catch (err) {
                        if (err.name === 'AbortError') return;
                    }
                }

                try {
                    await navigator.clipboard.writeText(shareData.url);
                    showToast('Link copied to clipboard!', 'success');
                } catch (err) {
                    const tmp = document.createElement('textarea');
                    tmp.value = shareData.url;
                    tmp.style.position = 'fixed';
                    tmp.style.opacity = '0';
                    document.body.appendChild(tmp);
                    tmp.select();
                    document.execCommand('copy');
                    tmp.remove();
                    showToast('Link copied to clipboard!', 'success');
                }
            });
        }
    </script>
@endpush

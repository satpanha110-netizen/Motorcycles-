@extends('layouts.app')

@section('title', 'Request Purchase')

@section('content')
    <section class="page-header py-4">
        <div class="container">
            <h1 class="fw-black mb-0"><i class="bi bi-bag-plus me-2 text-accent"></i>Request Purchase</h1>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-4 justify-content-center">
            {{-- Motorcycle summary --}}
            <div class="col-lg-5">
                <div class="bg-white border rounded-4 overflow-hidden">
                    <img src="{{ $motorcycle->image_url }}" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" alt="{{ $motorcycle->title }}">
                    <div class="p-4">
                        <h4 class="fw-bold">{{ $motorcycle->title }}</h4>
                        <p class="moto-meta mb-3">
                            {{ $motorcycle->year }} | {{ $motorcycle->engine_cc }}cc | {{ ucfirst($motorcycle->transmission) }}
                            &bull; {{ $motorcycle->location }}
                        </p>
                        <div class="price-tag fs-3">{{ $motorcycle->formatted_price }}</div>
                        <hr>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Submitting a request does not charge you anything. The seller will contact you to arrange payment and handover.
                        </small>
                    </div>
                </div>
            </div>

            {{-- Request form --}}
            <div class="col-lg-7">
                <div class="bg-white border rounded-4 p-4 p-lg-5">
                    <h4 class="fw-bold section-title mb-4">Your Details</h4>

                    
                    <form action="{{ route('orders.store', $motorcycle) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Full Name *</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}"
                                       class="form-control @error('customer_name') is-invalid @enderror" required>
                                @error('customer_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Phone Number *</label>
                                <input type="tel" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone) }}"
                                       class="form-control @error('customer_phone') is-invalid @enderror" required>
                                @error('customer_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Delivery Address *</label>
                            <input type="text" name="customer_address" value="{{ old('customer_address') }}"
                                   class="form-control @error('customer_address') is-invalid @enderror"
                                   placeholder="Street, district, city..." required>
                            @error('customer_address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Notes (optional)</label>
                            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Preferred meeting time, questions about the bike, trade-in offers...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-accent px-5 fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Submit Request
                            </button>
                            <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn btn-outline-dark px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

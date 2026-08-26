@php($isEdit = isset($motorcycle) && $motorcycle->exists)

<form action="{{ $isEdit ? route('seller.motorcycles.update', $motorcycle) : route('seller.motorcycles.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-4">
        {{-- Basic info --}}
        <div class="col-lg-8">
            <div class="bg-white border rounded-4 p-4 mb-4">
                <h5 class="fw-bold section-title mb-4">Basic Information</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $motorcycle->title ?? '') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="e.g. Honda Click 160" required>
                        @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold small">Brand *</label>
                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                            <option value="">Select brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $motorcycle->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold small">Category</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Optional</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $motorcycle->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Model *</label>
                        <input type="text" name="model" value="{{ old('model', $motorcycle->model ?? '') }}"
                               class="form-control @error('model') is-invalid @enderror" placeholder="e.g. Click 160" required>
                        @error('model')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Year *</label>
                        <input type="number" name="year" min="1950" max="{{ now()->year + 1 }}"
                               value="{{ old('year', $motorcycle->year ?? now()->year) }}"
                               class="form-control @error('year') is-invalid @enderror" required>
                        @error('year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Price (USD) *</label>
                        <input type="number" name="price" step="0.01" min="1"
                               value="{{ old('price', $motorcycle->price ?? '') }}"
                               class="form-control @error('price') is-invalid @enderror" placeholder="e.g. 3250" required>
                        @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Engine CC *</label>
                        <input type="number" name="engine_cc" min="49" max="2500"
                               value="{{ old('engine_cc', $motorcycle->engine_cc ?? '') }}"
                               class="form-control @error('engine_cc') is-invalid @enderror" placeholder="e.g. 160" required>
                        @error('engine_cc')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Mileage (km) *</label>
                        <input type="number" name="mileage" min="0"
                               value="{{ old('mileage', $motorcycle->mileage ?? 0) }}"
                               class="form-control @error('mileage') is-invalid @enderror" required>
                        @error('mileage')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Color</label>
                        <input type="text" name="color" value="{{ old('color', $motorcycle->color ?? '') }}"
                               class="form-control @error('color') is-invalid @enderror" placeholder="e.g. Red / Black">
                        @error('color')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Condition *</label>
                        <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                            <option value="used" {{ old('condition', $motorcycle->condition ?? 'used') === 'used' ? 'selected' : '' }}>Used</option>
                            <option value="new" {{ old('condition', $motorcycle->condition ?? '') === 'new' ? 'selected' : '' }}>New</option>
                        </select>
                        @error('condition')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Transmission *</label>
                        <select name="transmission" class="form-select @error('transmission') is-invalid @enderror" required>
                            <option value="automatic" {{ old('transmission', $motorcycle->transmission ?? '') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="manual" {{ old('transmission', $motorcycle->transmission ?? 'manual') === 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('transmission')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold small">Fuel Type *</label>
                        <select name="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror" required>
                            @foreach(['petrol', 'diesel', 'electric', 'hybrid'] as $fuel)
                                <option value="{{ $fuel }}" {{ old('fuel_type', $motorcycle->fuel_type ?? 'petrol') === $fuel ? 'selected' : '' }}>
                                    {{ ucfirst($fuel) }}
                                </option>
                            @endforeach
                        </select>
                        @error('fuel_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Location *</label>
                    <input type="text" name="location" value="{{ old('location', $motorcycle->location ?? auth()->user()->phone ? '' : '') }}"
                           class="form-control @error('location') is-invalid @enderror"
                           placeholder="e.g. Phnom Penh" required>
                    @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold small">Description *</label>
                    <textarea name="description" rows="5"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe the motorcycle's condition, service history, and anything a buyer should know..." required>{{ old('description', $motorcycle->description ?? '') }}</textarea>
                    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold small">Features <span class="text-muted">(one per line)</span></label>
                    <textarea name="features" rows="4"
                              class="form-control @error('features') is-invalid @enderror"
                              placeholder="ABS brakes&#10;LED lighting&#10;Digital dashboard">{{ old('features', isset($motorcycle) && $motorcycle->features ? implode("\n", $motorcycle->features) : '') }}</textarea>
                    @error('features')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Images + status --}}
        <div class="col-lg-4">
            <div class="bg-white border rounded-4 p-4 mb-4">
                <h5 class="fw-bold section-title mb-4">Images</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Main Image * <small class="text-muted">(JPG, PNG, WEBP — max 4MB)</small></label>
                    <img src="{{ $motorcycle->image_url ?? asset('images/placeholder-motorcycle.svg') }}" id="mainPreview"
                         class="w-100 rounded-3 border mb-2 {{ isset($motorcycle) && $motorcycle->main_image ? '' : 'd-none' }}"
                         style="aspect-ratio: 16/10; object-fit: cover;" alt="">
                    <label class="drop-zone-label">
                        <i class="bi bi-cloud-arrow-up fs-4 d-block mb-1"></i>
                        Click to upload main image
                        <input type="file" name="main_image" accept=".jpg,.jpeg,.png,.webp"
                               class="d-none" data-preview="mainPreview">
                    </label>
                    @error('main_image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold small">Gallery Images <small class="text-muted">(up to 6)</small></label>
                    <label class="drop-zone-label">
                        <i class="bi bi-images fs-4 d-block mb-1"></i>
                        Click to upload gallery images
                        <input type="file" name="gallery_images[]" accept=".jpg,.jpeg,.png,.webp" multiple class="d-none">
                    </label>
                    @error('gallery_images.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                @if(isset($motorcycle) && $motorcycle->images->isNotEmpty())
                    <hr>
                    <label class="form-label fw-semibold small">Current Gallery</label>
                    <div class="row g-2 row-cols-3">
                        @foreach($motorcycle->images as $image)
                            <div class="col">
                                <img src="{{ $image->url }}" class="w-100 rounded border" style="aspect-ratio: 1; object-fit: cover;" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white border rounded-4 p-4">
                <h5 class="fw-bold section-title mb-4">Status</h5>
                @if(auth()->user()->isAdmin())
                    <select name="status" class="form-select mb-3">
                        @foreach(['pending', 'approved', 'rejected', 'sold'] as $s)
                            <option value="{{ $s }}" {{ old('status', $motorcycle->status ?? 'pending') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        New listings are reviewed by an admin before going live.
                    </div>
                @endif

                <button type="submit" class="btn btn-accent w-100 fw-bold py-2">
                    <i class="bi bi-check-lg me-1"></i> {{ $isEdit ? 'Update Listing' : 'Publish Listing' }}
                </button>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold small">Full Name *</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror" required>
        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold small">Email *</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror" required>
        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold small">Phone *</label>
        <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
               class="form-control @error('phone') is-invalid @enderror" required>
        @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold small">
            Password @if(isset($user))<span class="text-muted">(leave blank to keep current)</span>@else *@endif
        </label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               {{ isset($user) ? '' : 'required' }} minlength="8">
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold small">Role *</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach(['customer', 'seller', 'admin'] as $r)
                <option value="{{ $r }}" {{ old('role', $user->role ?? 'customer') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold small">Status *</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $user->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold small">Profile Image</label>
        <input type="file" name="profile_image" accept=".jpg,.jpeg,.png,.webp"
               class="form-control @error('profile_image') is-invalid @enderror">
        @error('profile_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<button type="submit" class="btn btn-accent px-5 fw-bold">
    <i class="bi bi-check-lg me-1"></i>{{ $submitLabel }}
</button>

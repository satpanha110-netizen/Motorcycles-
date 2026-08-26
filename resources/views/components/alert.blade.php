@props(['type' => 'info', 'message'])

<div class="alert alert-{{ $type }} alert-flash d-flex align-items-center gap-2 shadow-sm" data-autohide role="alert">
    <i class="bi {{ $type === 'success' ? 'bi-check-circle-fill' : ($type === 'danger' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill') }} fs-5"></i>
    <div>{{ $message }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

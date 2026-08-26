@props(['icon', 'label', 'value', 'color' => 'accent'])

<div class="stat-card fade-up">
    <div class="stat-icon bg-opacity-10 text-{{ $color }}" style="background-color: rgba(var(--bs-{{ $color }}-rgb), .12);">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div>
        <div class="stat-value">{{ $value }}</div>
        <div class="stat-label">{{ $label }}</div>
    </div>
</div>

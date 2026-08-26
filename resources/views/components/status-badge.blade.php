@props(['status'])

<span {{ $attributes->merge(['class' => 'badge badge-status badge-status-' . $status]) }}>{{ ucfirst($status) }}</span>

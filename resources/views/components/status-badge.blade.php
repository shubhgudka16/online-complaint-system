@props(['status'])

@php
    $badgeConfig = match($status) {
        'Pending' => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'bi-clock-history'],
        'In Progress' => ['class' => 'bg-primary-subtle text-primary border border-primary-subtle', 'icon' => 'bi-gear-wide-connected'],
        'Resolved' => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'bi-check2-circle'],
        'Rejected' => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'bi-x-circle'],
        default => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'bi-question-circle'],
    };
@endphp

<span class="badge-pill-custom {{ $badgeConfig['class'] }}">
    <i class="bi {{ $badgeConfig['icon'] }}"></i>
    <span>{{ $status }}</span>
</span>

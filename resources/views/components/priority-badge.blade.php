@props(['priority'])

@php
    $priorityConfig = match($priority) {
        'High' => ['class' => 'bg-danger text-white', 'icon' => 'bi-fire'],
        'Medium' => ['class' => 'bg-warning text-dark', 'icon' => 'bi-exclamation-triangle-fill'],
        'Low' => ['class' => 'bg-secondary text-white', 'icon' => 'bi-arrow-down'],
        default => ['class' => 'bg-light text-dark', 'icon' => 'bi-dash'],
    };
@endphp

<span class="badge-pill-custom {{ $priorityConfig['class'] }}">
    <i class="bi {{ $priorityConfig['icon'] }}"></i>
    <span>{{ $priority }}</span>
</span>

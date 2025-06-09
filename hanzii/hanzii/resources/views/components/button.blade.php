@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'disabled' => false
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium focus:outline-none transition duration-150 ease-in-out';
    
    $variantClasses = [
        'primary' => 'bg-red-600 hover:bg-red-700 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
        'info' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'light' => 'bg-gray-200 hover:bg-gray-300 text-gray-800',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white',
        'outline-primary' => 'border border-red-600 text-red-600 hover:bg-red-50',
        'outline-secondary' => 'border border-gray-600 text-gray-600 hover:bg-gray-50',
        'outline-success' => 'border border-green-600 text-green-600 hover:bg-green-50',
        'outline-danger' => 'border border-red-600 text-red-600 hover:bg-red-50',
        'outline-warning' => 'border border-yellow-600 text-yellow-600 hover:bg-yellow-50',
        'outline-info' => 'border border-blue-600 text-blue-600 hover:bg-blue-50',
        'outline-light' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
        'outline-dark' => 'border border-gray-800 text-gray-800 hover:bg-gray-100',
        'link' => 'text-red-600 hover:text-red-700 underline',
    ];
    
    $sizeClasses = [
        'xs' => 'text-xs px-2 py-1 rounded',
        'sm' => 'text-sm px-3 py-1.5 rounded',
        'md' => 'text-base px-4 py-2 rounded-md',
        'lg' => 'text-lg px-5 py-2.5 rounded-md',
        'xl' => 'text-xl px-6 py-3 rounded-lg',
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
    
    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed';
    }

    $attributes = $attributes->merge(['class' => $classes]);
@endphp

@if ($href && !$disabled)
    <a href="{{ $href }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes }} @if($disabled) disabled @endif>
        {{ $slot }}
    </button>
@endif 
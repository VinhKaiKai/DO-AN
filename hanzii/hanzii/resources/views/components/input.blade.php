@props([
    'type' => 'text',
    'name' => '',
    'label' => null,
    'value' => null,
    'placeholder' => '',
    'error' => null,
    'disabled' => false,
    'required' => false,
    'id' => null,
    'helpText' => null
])

@php
    $id = $id ?? $name;
    $inputClasses = 'block w-full rounded-md shadow-sm border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition duration-150 ease-in-out';
    
    if ($error) {
        $inputClasses .= ' border-red-300 text-red-900 placeholder-red-300';
    }
    
    if ($disabled) {
        $inputClasses .= ' opacity-50 cursor-not-allowed bg-gray-100';
    }
@endphp

<div {{ $attributes }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}"
        @if($disabled) disabled @endif
        @if($required) required @endif
        {{ $attributes->merge(['class' => $inputClasses]) }}
    />
    
    @if($helpText)
        <p class="mt-1 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div> 
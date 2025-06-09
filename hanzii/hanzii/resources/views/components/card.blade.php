@props(['title' => null, 'footer' => null, 'headerActions' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm overflow-hidden']) }}>
    @if($title || $headerActions)
        <div class="border-b px-4 py-3 flex justify-between items-center">
            @if($title)
                <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
            @endif
            
            @if($headerActions)
                <div class="flex items-center">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="p-4">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="bg-gray-50 px-4 py-3 border-t">
            {{ $footer }}
        </div>
    @endif
</div> 
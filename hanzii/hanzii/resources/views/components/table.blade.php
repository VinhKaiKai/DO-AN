@props(['headers' => [], 'striped' => true, 'hover' => true, 'footer' => null])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        @if(count($headers) > 0)
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        
        <tbody @if($striped) class="bg-white divide-y divide-gray-200" @endif>
            {{ $slot }}
        </tbody>
        
        @if($footer)
            <tfoot class="bg-gray-50">
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>

@if($hover)
    <style>
        tbody tr:hover {
            background-color: rgba(243, 244, 246, 0.7);
        }
    </style>
@endif 
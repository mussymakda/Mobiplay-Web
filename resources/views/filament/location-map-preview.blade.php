@php
    $record = $getRecord();
    $lat = $record->latitude;
    $lng = $record->longitude;
@endphp

<div class="flex flex-col space-y-2">
    <div class="bg-gray-50 rounded-lg p-3 border text-sm">
        <div class="flex items-center space-x-2 mb-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-medium text-gray-700">GPS Location</span>
        </div>
        
        <div class="text-xs text-gray-600 mb-3 font-mono">
            {{ number_format($lat, 6) }}, {{ number_format($lng, 6) }}
        </div>
        
        <div class="flex gap-1">
            <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&z=15" 
               target="_blank" 
               class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                🗺️ View
            </a>
            <button onclick="navigator.clipboard.writeText('{{ $lat }},{{ $lng }}')" 
                    class="inline-flex items-center px-2 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 transition-colors">
                📋 Copy
            </button>
        </div>
    </div>
</div>
<div class="space-y-4">
    @if (empty($logs) || count($logs) === 0)
        <div class="text-center py-8">
            <div class="text-gray-500 text-lg">{{ $message ?? 'No location history available.' }}</div>
        </div>
    @else
        <div class="overflow-hidden">
            <h3 class="text-lg font-semibold mb-4">Recent Location History (Last 10 Updates)</h3>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach ($logs as $log)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        📍 {{ $log->source ?? 'GPS' }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $log->created_at->format('M j, Y g:i A') }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Coordinates:</span>
                                        <div class="text-gray-600 dark:text-gray-400">
                                            {{ number_format($log->latitude, 6) }}, {{ number_format($log->longitude, 6) }}
                                        </div>
                                    </div>
                                    
                                    @if ($log->accuracy)
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Accuracy:</span>
                                            <div class="text-gray-600 dark:text-gray-400">{{ $log->accuracy }}m</div>
                                        </div>
                                    @endif
                                    
                                    @if ($log->speed)
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Speed:</span>
                                            <div class="text-gray-600 dark:text-gray-400">{{ number_format($log->speed, 1) }} km/h</div>
                                        </div>
                                    @endif
                                    
                                    @if ($log->distance_from_previous)
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Distance Traveled:</span>
                                            <div class="text-gray-600 dark:text-gray-400">{{ number_format($log->distance_from_previous, 2) }} km</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="ml-4">
                                <a href="https://www.google.com/maps/@{{ $log->latitude }},{{ $log->longitude }},15z" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    View on Map
                                </a>
                            </div>
                        </div>
                        
                        @if ($log->is_suspicious)
                            <div class="mt-2 px-2 py-1 bg-red-100 text-red-800 text-xs rounded">
                                ⚠️ Flagged as suspicious movement
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 text-sm text-gray-500 text-center">
                Showing last 10 location updates. 
                <span class="font-medium">Total logs: {{ $logs->first()?->driver?->locationLogs()?->count() ?? 0 }}</span>
            </div>
        </div>
    @endif
</div>
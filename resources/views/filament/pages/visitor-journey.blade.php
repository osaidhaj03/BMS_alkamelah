<x-filament-panels::page>
    <div class="space-y-4">
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <h3 class="text-lg font-bold mb-2">
                الجلسة: {{ Str::limit($sessionId, 20) }}
            </h3>
            <p class="text-sm text-gray-500">
                IP: {{ $visits->first()?->ip_address ?? '-' }}
                | عدد الصفحات: {{ $visits->count() }}
                @if($visits->sum('duration_seconds') > 0)
                    | إجمالي الوقت: {{ gmdate('H:i:s', $visits->sum('duration_seconds')) }}
                @endif
            </p>
        </div>

        <div class="relative pr-8">
            @foreach($visits as $index => $visit)
                <div class="flex items-start gap-4 mb-6 relative">
                    {{-- الخط العمودي --}}
                    @if(!$loop->last)
                        <div class="absolute right-3 top-10 w-0.5 h-full bg-gray-300 dark:bg-gray-600"></div>
                    @endif

                    {{-- النقطة --}}
                    <div class="relative z-10 w-7 h-7 rounded-full flex items-center justify-center text-white text-xs
                        {{ $loop->first ? 'bg-green-500' : ($loop->last ? 'bg-red-500' : 'bg-blue-500') }}">
                        {{ $index + 1 }}
                    </div>

                    {{-- المحتوى --}}
                    <div class="flex-1 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold">
                                    {{ $visit->page_title ?? $visit->route_name ?? 'صفحة غير معروفة' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1 truncate max-w-md" dir="ltr">
                                    {{ $visit->url }}
                                </p>
                            </div>
                            <div class="text-left text-sm">
                                <span class="text-gray-500">{{ $visit->visited_at?->format('H:i:s') }}</span>
                                @if($visit->duration_seconds)
                                    <br>
                                    <span class="text-blue-500 font-medium">
                                        {{ gmdate('i:s', $visit->duration_seconds) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>

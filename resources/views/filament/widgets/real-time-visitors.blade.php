<x-filament-widgets::widget>
    @php $data = $this->getVisitorsData(); @endphp
    <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
            <h3 class="text-lg font-bold">الآن على الموقع: {{ $data['count'] }} زائر</h3>
        </div>
        @if($data['pages']->count())
            <div class="space-y-2">
                @foreach($data['pages'] as $page)
                    <div class="flex justify-between items-center py-1 px-3 rounded bg-gray-50 dark:bg-gray-700">
                        <span>{{ $page->page_title ?? $page->route_name ?? '/' }}</span>
                        <span class="text-sm font-semibold text-blue-500">{{ $page->visitors }} زائر</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::widget>

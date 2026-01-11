@props([])

<div class="book-content-reader" x-data="{ 
        init() {
            // الاستماع لتغيير الصفحة والتمرير لأعلى
            Livewire.on('page-changed', () => {
                document.getElementById('book-content-wrapper')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            
            // التنقل بالكيبورد
            document.addEventListener('keydown', (e) => {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                
                if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    $wire.nextPageAction();
                }
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    $wire.previousPageAction();
                }
            });
        }
    }" wire:loading.class="opacity-50">
    {{-- Main Content Area --}}
    <main class="flex-1 overflow-y-auto" style="height: calc(100vh - 80px); background-color: var(--bg-body);">
        <div class="max-w-4xl mx-auto p-2 lg:p-8">
            <div class="p-0">

                {{-- Page Content --}}
                <div id="book-content-wrapper" class="space-y-8">
                    @if($currentPage)
                        <div class="rounded-lg shadow-lg p-2 lg:p-8 relative page-container transition-transform duration-300 hover:shadow-xl"
                            style="background-color: var(--bg-paper); box-shadow: var(--shadow-paper); font-family: var(--font-main);"
                            data-page="{{ $currentPageNum }}">

                            {{-- Content --}}
                            <div class="prose prose-lg max-w-none leading-loose"
                                style="color: var(--text-main); line-height: 2;">
                                {!! $currentPage->html_content ?? nl2br(e($currentPage->content)) !!}
                            </div>
                        </div>
                    @else
                        {{-- No Content Message --}}
                        <div class="rounded-lg shadow-lg p-2 lg:p-8 text-center"
                            style="background-color: var(--bg-paper); box-shadow: var(--shadow-paper);">
                            <div class="text-6xl mb-4">📖</div>
                            <p class="text-gray-500 text-lg" style="font-family: var(--font-ui);">
                                لم يتم العثور على محتوى لهذه الصفحة
                            </p>
                            <p class="text-gray-400 text-sm mt-2" style="font-family: var(--font-ui);">
                                حاول الانتقال إلى صفحة أخرى
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    {{-- Loading Indicator --}}
    <div wire:loading class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="bg-white rounded-lg shadow-lg p-4 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span style="font-family: var(--font-ui);">جارٍ التحميل...</span>
        </div>
    </div>

    {{-- Bottom Progress Bar --}}
    @php
        $progress = $totalPages > 0 ? round(($currentPageNum / $totalPages) * 100) : 0;
        $volumes = $book?->volumes ?? collect();
    @endphp

    <div class="fixed bottom-4 left-4 z-30">
        <div class="flex items-center gap-3 px-4 py-2 rounded-full shadow-lg"
            style="background-color: var(--bg-sidebar); border: 1px solid var(--border-color); box-shadow: var(--shadow-soft);">

            {{-- Circular Progress Indicator --}}
            <div class="relative w-10 h-10">
                <svg class="w-10 h-10 transform -rotate-90" viewBox="0 0 36 36">
                    {{-- Background Circle --}}
                    <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none"
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />

                    {{-- Progress Circle --}}
                    @php
                        $circumference = 2 * 3.14159 * 15.9155;
                        $strokeDasharray = ($progress / 100) * $circumference . ', ' . $circumference;
                    @endphp
                    <path class="transition-all duration-300" stroke="var(--accent-color)" stroke-width="3"
                        stroke-linecap="round" fill="none" stroke-dasharray="{{ $strokeDasharray }}"
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>

                {{-- Percentage Text --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xs font-bold" style="color: var(--accent-color); font-family: var(--font-ui);">
                        {{ $progress }}%
                    </span>
                </div>
            </div>

            {{-- Volume Selector (Desktop) --}}
            @if($volumes->isNotEmpty())
                <div class="hidden lg:block border-l border-gray-200 pl-3 ml-1" style="border-color: var(--border-color);">
                    <select class="bg-transparent text-sm focus:outline-none cursor-pointer"
                        style="font-family: var(--font-ui); color: var(--text-main);"
                        wire:change="goToPage($event.target.selectedOptions[0].dataset.page)">
                        @foreach($volumes as $vol)
                            <option value="{{ $vol->id }}" data-page="{{ $vol->pages()->min('page_number') ?? 1 }}">
                                {{ $vol->display_name ?? 'المجلد ' . $vol->number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Page Counter --}}
            <div class="flex items-center text-sm gap-1"
                style="font-family: var(--font-ui); color: var(--text-secondary);">
                <span>صفحة</span>
                <input type="number" value="{{ $currentPageNum }}" min="1" max="{{ $totalPages }}"
                    class="w-12 text-center bg-transparent border-b border-gray-300 focus:border-green-600 focus:outline-none transition-colors"
                    style="color: var(--text-main);" wire:change="goToPage($event.target.value)">
                <span>من {{ $totalPages }}</span>
            </div>

            {{-- Navigation Buttons --}}
            <div class="flex items-center gap-1">
                {{-- Previous Page --}}
                @if($previousPage)
                    <button wire:click="previousPageAction" class="p-1.5 rounded hover:bg-gray-100 transition-colors"
                        title="الصفحة السابقة">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <span class="p-1.5 text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"></path>
                        </svg>
                    </span>
                @endif

                {{-- Next Page --}}
                @if($nextPage)
                    <button wire:click="nextPageAction" class="p-1.5 rounded hover:bg-gray-100 transition-colors"
                        title="الصفحة التالية">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                @else
                    <span class="p-1.5 text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7">
                            </path>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
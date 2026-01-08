<div>
    {{-- Search and Filters Row --}}
    @if($showSearch)
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">
                {{-- Search Bar (Full Width) --}}
                <div class="flex-1 relative">
                    <div class="relative flex items-center bg-white border-2 border-gray-200 rounded-xl focus-within:border-green-500 transition-colors">
                        <div class="absolute right-4 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="ابحث في عناوين الكتب أو المؤلفين أو اسماء الأقسام..."
                            class="w-full px-4 py-3 pr-12 text-right bg-transparent border-none rounded-xl focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400">
                    </div>
                </div>

                {{-- Filter Button --}}
                <div class="flex items-center gap-3">
                    <button wire:click="$toggle('filterModalOpen')" 
                        class="flex items-center gap-2 px-4 py-3 bg-white border-2 border-gray-200 rounded-xl hover:border-green-500 transition-colors relative">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">تصفية</span>
                        @if($this->getActiveFiltersCount() > 0)
                            <span class="absolute -top-2 -right-2 bg-green-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $this->getActiveFiltersCount() }}
                            </span>
                        @endif
                    </button>

                    {{-- Per Page Selector --}}
                    @if($showPerPageSelector)
                        <div class="flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 rounded-xl">
                            <span class="text-sm text-gray-600">عرض</span>
                            <select wire:model.live="perPage"
                                class="border-none bg-transparent px-1 py-1 text-sm focus:outline-none focus:ring-0 text-gray-800 font-medium">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-sm text-gray-600">نتيجة</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Active Filters Display --}}
            @if(count($sectionFilters) > 0 || count($authorFilters) > 0)
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach($sectionFilters as $sectionId)
                        @php $sec = $sections->find($sectionId); @endphp
                        @if($sec)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                {{ $sec->name }}
                                <button wire:click="toggleSectionFilter({{ $sectionId }})" class="hover:text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        @endif
                    @endforeach
                    @foreach($authorFilters as $authorId)
                        @php $auth = $authors->find($authorId); @endphp
                        @if($auth)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                {{ $auth->full_name }}
                                <button wire:click="toggleAuthorFilter({{ $authorId }})" class="hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        @endif
                    @endforeach
                    <button wire:click="clearAllFilters" class="text-sm text-red-600 hover:text-red-800 hover:underline">
                        مسح الكل
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- Filter Buttons (Old Style - for homepage) --}}
    @if($showFilters && !$showSearch)
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('home', ['type' => 'books', 'section' => 'الفقه-الحنفي']) }}"
                class="bg-white text-green-800 border border-green-800 px-5 py-2 rounded-full transition-colors duration-300 hover:bg-green-800 hover:text-white">
                كتب الفقه الحنفي
            </a>
            <a href="{{ route('books.index') }}"
                class="bg-white text-green-800 border border-green-800 px-5 py-2 rounded-full transition-colors duration-300 hover:bg-green-800 hover:text-white">
                عرض جميع الكتب
            </a>
        </div>
    @endif

    {{-- Books Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden" wire:loading.class="opacity-50"
        wire:target="search,perPage,section,previousPage,nextPage,toggleSectionFilter,toggleAuthorFilter,clearAllFilters">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-50">
                    <tr>
                        <th scope="col"
                            class="px-3 py-3 text-right font-medium text-gray-900 uppercase tracking-wider w-12"
                            style="font-size: 1rem;">
                            #
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right font-medium text-gray-900 uppercase tracking-wider min-w-[280px]"
                            style="font-size: 1rem;">
                            اسم الكتاب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right font-medium text-gray-900 uppercase tracking-wider w-48"
                            style="font-size: 1rem;">
                            المؤلف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right font-medium text-gray-900 uppercase tracking-wider w-40"
                            style="font-size: 1rem;">
                            القسم
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right font-medium text-gray-900 uppercase tracking-wider w-36"
                            style="font-size: 1rem;">
                            الناشر
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-center font-medium text-gray-900 uppercase tracking-wider w-24"
                            style="font-size: 0.9rem;">
                            الصفحات
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-center font-medium text-gray-900 uppercase tracking-wider w-24"
                            style="font-size: 0.9rem;">
                            الأجزاء
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($books as $index => $book)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-3 py-4 whitespace-nowrap text-gray-900 w-12" style="font-size: 1.1rem;">
                                {{ ($books->currentPage() - 1) * $books->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900" style="font-size: 1.1rem;">
                                    <a href="{{ route('book.read', ['bookId' => $book->id]) }}"
                                        class="text-green-700 hover:text-green-900 hover:underline transition-colors duration-200">
                                        {!! $this->highlightText($book->title, $search) !!}
                                    </a>
                                </div>
                                @if($book->subtitle)
                                    <div class="text-gray-500 text-sm">
                                        {!! $this->highlightText($book->subtitle, $search) !!}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900" style="font-size: 1rem;">
                                    @if($book->authors->isNotEmpty())
                                        @php
                                            $authorName = $book->authors->first()->full_name ?? '';
                                            $words = explode(' ', $authorName);
                                            $truncated = count($words) > 5 ? implode(' ', array_slice($words, 0, 5)) . '...' : $authorName;
                                        @endphp
                                        <a href="#" title="{{ $authorName }}"
                                            class="text-green-700 hover:text-green-900 hover:underline transition-colors duration-200">
                                            {!! $this->highlightText($truncated, $search) !!}
                                        </a>
                                    @else
                                        <span class="text-gray-400">غير محدد</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $sectionName = $book->bookSection->name ?? 'غير محدد';
                                    $sectionWords = explode(' ', $sectionName);
                                    $sectionTruncated = count($sectionWords) > 4 ? implode(' ', array_slice($sectionWords, 0, 4)) . '...' : $sectionName;
                                @endphp
                                <span title="{{ $sectionName }}"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800 text-sm whitespace-nowrap">
                                    {{ $sectionTruncated }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $publisherName = $book->publisher->name ?? '—';
                                    $pubWords = explode(' ', $publisherName);
                                    $pubTruncated = count($pubWords) > 4 ? implode(' ', array_slice($pubWords, 0, 4)) . '...' : $publisherName;
                                @endphp
                                <span title="{{ $publisherName }}" class="text-gray-700 text-sm">
                                    {{ $pubTruncated }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center w-24">
                                <span class="text-gray-600 text-sm font-medium">
                                    {{ number_format($book->pages_count ?? 0) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center w-24">
                                <span class="text-gray-600 text-sm font-medium">
                                    {{ $book->volumes_count ?? 0 }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium text-gray-900 mb-1" style="font-size: 1.2rem;">لا توجد كتب متوفرة
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $search ? 'جرب البحث بكلمات أخرى' : 'سيتم إضافة الكتب قريباً' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($showPagination && ($books->hasPages() || $books->count() > 0))
            <div class="px-6 py-4 flex items-center justify-center border-t border-gray-200 bg-gray-50">
                {{-- Navigation Buttons --}}
                <div class="flex items-center gap-2">
                    @if($books->hasPages())
                        @if($books->onFirstPage())
                            <button disabled class="p-2 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @else
                            <button wire:click="previousPage"
                                class="p-2 rounded-full hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif

                        <span class="text-sm text-gray-600 mx-2">
                            {{ $books->firstItem() ?: 0 }}-{{ $books->lastItem() ?: 0 }} من {{ $books->total() }}
                        </span>

                        @if($books->hasMorePages())
                            <button wire:click="nextPage"
                                class="p-2 rounded-full hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                    </path>
                                </svg>
                            </button>
                        @else
                            <button disabled class="p-2 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                    </path>
                                </svg>
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Filter Modal --}}
    @if($filterModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('filterModalOpen', false)"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-right shadow-xl transition-all w-full max-w-xl flex flex-col max-h-[80vh]">
                    
                    {{-- Header --}}
                    <div class="bg-white px-6 pt-5 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold leading-6 text-gray-900">تصفية النتائج</h3>
                            <button wire:click="$set('filterModalOpen', false)" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Tabs --}}
                        <div class="flex border-b border-gray-200">
                            <button wire:click="$set('activeFilterTab', 'sections')"
                                    class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors duration-200 {{ $activeFilterTab === 'sections' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                الأقسام
                                @if(count($sectionFilters) > 0)
                                    <span class="mr-1 text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full">{{ count($sectionFilters) }}</span>
                                @endif
                            </button>
                            <button wire:click="$set('activeFilterTab', 'authors')"
                                    class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors duration-200 {{ $activeFilterTab === 'authors' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                المؤلفين
                                @if(count($authorFilters) > 0)
                                    <span class="mr-1 text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full">{{ count($authorFilters) }}</span>
                                @endif
                            </button>
                        </div>
                    </div>

                    {{-- Search & Content --}}
                    <div class="flex-1 overflow-hidden flex flex-col bg-gray-50">
                        {{-- Inner Search --}}
                        <div class="px-6 py-4 bg-white border-b border-gray-100">
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="filterSearch"
                                       class="block w-full rounded-lg border-gray-300 pr-10 focus:border-green-500 focus:ring-green-500 text-sm bg-gray-50" 
                                       placeholder="{{ $activeFilterTab === 'sections' ? 'بحث في الأقسام...' : 'بحث في المؤلفين...' }}">
                            </div>
                        </div>

                        {{-- List Area --}}
                        <div class="flex-1 overflow-y-auto p-4 max-h-64">
                            <ul class="space-y-1">
                                @if($activeFilterTab === 'sections')
                                    @forelse($sections as $sec)
                                        <li class="relative flex items-start py-2 px-4 hover:bg-white hover:shadow-sm rounded-lg transition-all cursor-pointer"
                                            wire:click="toggleSectionFilter({{ $sec->id }})">
                                            <div class="min-w-0 flex-1 text-sm">
                                                <label class="select-none font-medium text-gray-900 cursor-pointer">{{ $sec->name }}</label>
                                            </div>
                                            <div class="mr-3 flex h-6 items-center">
                                                <div class="relative flex items-center justify-center w-5 h-5 border rounded transition-colors {{ in_array($sec->id, $sectionFilters) ? 'bg-green-600 border-green-600' : 'bg-white border-gray-300' }}">
                                                    @if(in_array($sec->id, $sectionFilters))
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="py-8 text-center text-gray-500 text-sm">لا توجد أقسام</li>
                                    @endforelse
                                @else
                                    @forelse($authors as $auth)
                                        <li class="relative flex items-start py-2 px-4 hover:bg-white hover:shadow-sm rounded-lg transition-all cursor-pointer"
                                            wire:click="toggleAuthorFilter({{ $auth->id }})">
                                            <div class="min-w-0 flex-1 text-sm">
                                                <label class="select-none font-medium text-gray-900 cursor-pointer">{{ $auth->full_name }}</label>
                                            </div>
                                            <div class="mr-3 flex h-6 items-center">
                                                <div class="relative flex items-center justify-center w-5 h-5 border rounded transition-colors {{ in_array($auth->id, $authorFilters) ? 'bg-green-600 border-green-600' : 'bg-white border-gray-300' }}">
                                                    @if(in_array($auth->id, $authorFilters))
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="py-8 text-center text-gray-500 text-sm">لا توجد نتائج</li>
                                    @endforelse
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-white px-6 py-3 gap-3 flex flex-row-reverse border-t border-gray-100">
                        <button type="button" 
                                class="inline-flex justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors"
                                wire:click="$set('filterModalOpen', false)">
                            تطبيق
                        </button>
                        <button type="button" 
                                class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                                wire:click="$set('filterModalOpen', false)">
                            إلغاء
                        </button>
                        <button type="button" 
                                class="mr-auto text-sm text-gray-500 hover:text-red-600 transition-colors"
                                wire:click="clearAllFilters">
                            مسح الكل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
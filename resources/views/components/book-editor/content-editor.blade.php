@props([
    'currentPage' => null,
    'pages' => collect(),
    'book' => null,
    'currentPageNum' => 1,
    'totalPages' => 1,
    'nextPage' => null,
    'previousPage' => null
])

<main class="flex-1 overflow-y-auto" style="height: calc(100vh - 80px); background-color: var(--bg-body);">
    <div class="max-w-4xl mx-auto p-2 lg:p-8">
        <!-- Content Wrapper -->
        <div class="p-0">
            
            <!-- Page Content Editor -->
            <div id="book-content-wrapper" class="space-y-8">
                @if($currentPage)
                    <div class="rounded-lg shadow-lg p-2 lg:p-8 relative page-container" 
                         style="background-color: var(--bg-paper); box-shadow: var(--shadow-paper);"
                         data-page="{{ $currentPageNum }}">
                         
                        <!-- Page Header -->
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                            <span class="text-xs text-gray-400" style="font-family: var(--font-ui);">
                                {{ $book->title ?? 'الكتاب' }} - {{ $book->authors->first()->full_name ?? 'المؤلف' }}
                            </span>
                            <span class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-full font-bold">
                                وضع التحرير
                            </span>
                        </div>
                        
                        <!-- Chapter Title if available -->
                        @if($currentPage->chapter)
                            <h3 class="text-xl font-bold mb-4" style="color: var(--accent-color); font-family: var(--font-ui);">
                                {{ $currentPage->chapter->title }}
                            </h3>
                        @endif
                        
                        <!-- Editable Content -->
                        <div id="content-editor" 
                             class="content-editor prose prose-lg max-w-none leading-loose pb-8" 
                             contenteditable="true"
                             @input="markModified()"
                             style="color: var(--text-main); line-height: 2;">
                            {!! $currentPage->html_content ?? nl2br(e($currentPage->content)) !!}
                        </div>

                        <!-- Page Footer - Original Page Number -->
                        <div class="flex justify-center items-center mt-8 pt-6 border-t border-gray-100 text-gray-400 font-bold" style="font-family: var(--font-ui);">
                            <span class="text-lg">{{ $currentPage->original_page_number }}</span>
                        </div>
                    </div>
                @else
                    <!-- No Content Message -->
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
            
            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between mt-8 gap-4">
                <!-- Previous Page Button -->
                @if($previousPage)
                    <a href="{{ route('book.edit', ['bookId' => $book->id, 'pageNumber' => $previousPage->page_number]) }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl shadow-md hover:bg-gray-50 hover:border-red-300 transition-all"
                       style="font-family: var(--font-ui);">
                        <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>الصفحة السابقة</span>
                    </a>
                @else
                    <div class="px-6 py-3"></div>
                @endif
                
                <!-- Page Jump -->
                <div class="flex items-center gap-2" style="font-family: var(--font-ui);">
                    <span class="text-sm text-gray-500">صفحة</span>
                    <input type="number" 
                           id="page-jump-input"
                           min="1" 
                           max="{{ $totalPages }}" 
                           value="{{ $currentPageNum }}"
                           class="w-16 px-2 py-2 border border-gray-200 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                           onchange="window.location.href='/editBook/{{ $book->id }}/' + this.value">
                    <span class="text-sm text-gray-500">من {{ $totalPages }}</span>
                </div>
                
                <!-- Next Page Button -->
                @if($nextPage)
                    <a href="{{ route('book.edit', ['bookId' => $book->id, 'pageNumber' => $nextPage->page_number]) }}" 
                       class="flex items-center gap-2 px-6 py-3 text-white rounded-xl shadow-md hover:opacity-90 transition-all"
                       style="background-color: var(--accent-color); font-family: var(--font-ui);">
                        <span>الصفحة التالية</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @else
                    <div class="px-6 py-3"></div>
                @endif
            </div>
        </div>
    </div>
</main>

<!-- Keyboard Navigation -->
<script>
    document.addEventListener('keydown', function(e) {
        // Don't trigger navigation when editing content
        if (e.target.id === 'content-editor' || e.target.tagName === 'INPUT') return;
        
        // Arrow Left/Up = Next Page (RTL)
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            @if($nextPage)
                window.location.href = "{{ route('book.edit', ['bookId' => $book->id, 'pageNumber' => $nextPage->page_number]) }}";
            @endif
        }
        // Arrow Right/Down = Previous Page (RTL)
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            @if($previousPage)
                window.location.href = "{{ route('book.edit', ['bookId' => $book->id, 'pageNumber' => $previousPage->page_number]) }}";
            @endif
        }
        
        // Ctrl+S = Save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            // Trigger save from Alpine component
            const alpineData = document.querySelector('[x-data]').__x.$data;
            if (alpineData && alpineData.saveContent) {
                alpineData.saveContent();
            }
        }
    });
</script>

@props([
    'chapters' => collect(),
    'book' => null,
    'currentPage' => null
])

<aside class="w-80 border-l overflow-y-auto bg-white hidden lg:block" 
       style="border-color: var(--border-color); background-color: var(--bg-sidebar); height: calc(100vh - 80px);">
    <div class="p-4">
        <!-- Sidebar Header -->
        <div class="mb-4 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg" style="color: var(--accent-color); font-family: var(--font-ui);">
                    فهرس المحتويات
                </h3>
                <p class="text-xs text-gray-500 mt-1" style="font-family: var(--font-ui);">
                    {{ $chapters->count() }} فصل رئيسي
                </p>
            </div>
            <!-- Expand All / Collapse All -->
            <div class="flex gap-1">
                <button onclick="expandAllChapters()" 
                        class="p-1.5 rounded hover:bg-gray-100 text-gray-500 transition-colors" 
                        title="توسيع الكل">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <button onclick="collapseAllChapters()" 
                        class="p-1.5 rounded hover:bg-gray-100 text-gray-500 transition-colors" 
                        title="طي الكل">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="mb-4 relative">
            <input type="text" 
                   id="toc-search" 
                   placeholder="ابحث في فهرس المحتويات ..." 
                   class="w-full p-2 pl-9 rounded-lg border text-sm transition-colors"
                   style="border-color: var(--border-color); font-family: var(--font-ui); background-color: var(--bg-paper); color: var(--text-main);"
                   onfocus="this.style.borderColor = 'var(--accent-color)';"
                   onblur="this.style.borderColor = 'var(--border-color)';">
            <div class="absolute top-2.5 left-3 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Table of Contents List -->
        <ul class="space-y-1 toc-list" style="font-family: var(--font-ui);">
            @forelse($chapters as $chapter)
                <x-book.chapter-item 
                    :chapter="$chapter" 
                    :book="$book" 
                    :currentPage="$currentPage" 
                    :originalPageMap="$originalPageMap ?? []"
                    :level="0" 
                />
            @empty
                <!-- No Chapters Available -->
                <li class="p-4 text-center">
                    <div class="text-4xl mb-2">📑</div>
                    <p class="text-gray-500 text-sm">لا يوجد فهرس متاح لهذا الكتاب</p>
                </li>
            @endforelse
        </ul>
        
        <!-- Book Info Section -->
        @if($book)
            <div class="mt-6 p-4 rounded-lg border" style="border-color: var(--border-color); background-color: var(--bg-paper);">
                <h4 class="font-bold mb-3 text-sm" style="color: var(--accent-color); font-family: var(--font-ui);">
                    معلومات الكتاب
                </h4>
                <div class="space-y-2 text-xs" style="font-family: var(--font-ui); color: var(--text-secondary);">
                    <div class="flex justify-between">
                        <span>المؤلف:</span>
                        <span class="font-medium" style="color: var(--text-main);">{{ $book->authors?->first()?->full_name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>عدد الصفحات:</span>
                        <span class="font-medium" style="color: var(--text-main);">{{ $book->pages_count ?? $book->pages()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>القسم:</span>
                        <span class="font-medium" style="color: var(--text-main);">{{ $book->bookSection?->name ?? 'غير مصنف' }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="sidebar-overlay">
    <aside class="w-80 h-full bg-white overflow-y-auto" style="background-color: var(--bg-sidebar);">
        <div class="p-4">
            <!-- Close Button -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg" style="color: var(--accent-color); font-family: var(--font-ui);">
                    فهرس المحتويات
                </h3>
                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors" id="close-sidebar">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Search Bar -->
            <div class="mb-4 relative">
                <input type="text" 
                       id="toc-search-mobile" 
                       placeholder="ابحث في الفهرس ..." 
                       class="w-full p-2 pl-9 rounded-lg border text-sm transition-colors"
                       style="border-color: var(--border-color); font-family: var(--font-ui); background-color: var(--bg-paper); color: var(--text-main);">
                <div class="absolute top-2.5 left-3 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Mobile Table of Contents List -->
            <ul class="space-y-1 mobile-toc-list" style="font-family: var(--font-ui);">
                @forelse($chapters as $chapter)
                    <x-book.chapter-item 
                        :chapter="$chapter" 
                        :book="$book" 
                        :currentPage="$currentPage" 
                        :level="0" 
                    />
                @empty
                    <li class="p-4 text-center text-gray-500 text-sm">
                        لا يوجد فهرس متاح
                    </li>
                @endforelse
            </ul>
        </div>
    </aside>
</div>

<script>
// Toggle individual chapter expand/collapse
function toggleChapter(chapterId) {
    const childrenEl = document.getElementById('children-' + chapterId);
    const parentLi = childrenEl?.closest('.toc-item');
    const expandBtn = parentLi?.querySelector('.expand-btn svg');
    
    if (childrenEl) {
        childrenEl.classList.toggle('hidden');
        
        // Rotate arrow icon - for RTL: default points left, rotate -90deg to point down when expanded
        if (expandBtn) {
            if (childrenEl.classList.contains('hidden')) {
                expandBtn.style.transform = 'rotate(0deg)';
            } else {
                expandBtn.style.transform = 'rotate(-90deg)';
            }
        }
    }
}

// Expand all chapters
function expandAllChapters() {
    document.querySelectorAll('.children-list').forEach(el => {
        el.classList.remove('hidden');
    });
    document.querySelectorAll('.expand-btn svg').forEach(svg => {
        svg.style.transform = 'rotate(-90deg)';
    });
}

// Collapse all chapters
function collapseAllChapters() {
    document.querySelectorAll('.children-list').forEach(el => {
        el.classList.add('hidden');
    });
    document.querySelectorAll('.expand-btn svg').forEach(svg => {
        svg.style.transform = 'rotate(0deg)';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const closeSidebar = document.getElementById('close-sidebar');
    
    // Toggle Sidebar (TOC)
    if(menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebarOverlay?.classList.remove('hidden');
        });
    }
    
    // Close Sidebar (TOC)
    if(closeSidebar) {
        closeSidebar.addEventListener('click', function() {
            sidebarOverlay?.classList.add('hidden');
        });
    }
    
    // Close on click outside (TOC)
    sidebarOverlay?.addEventListener('click', function(e) {
        if(e.target === sidebarOverlay) {
            sidebarOverlay.classList.add('hidden');
        }
    });

    // TOC Search Logic
    function setupSearch(inputId, listClass) {
        const searchInput = document.getElementById(inputId);
        const tocList = document.querySelector('.' + listClass); 
        
        if(searchInput && tocList) {
            const items = tocList.querySelectorAll('.toc-item');
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.trim().toLowerCase();
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if(text.includes(term)) {
                        item.style.display = '';
                        // Also show parent chapters
                        let parent = item.parentElement?.closest('.toc-item');
                        while(parent) {
                            parent.style.display = '';
                            const childList = parent.querySelector('.children-list');
                            if(childList) childList.classList.remove('hidden');
                            parent = parent.parentElement?.closest('.toc-item');
                        }
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    }
    
    setupSearch('toc-search', 'toc-list');
    setupSearch('toc-search-mobile', 'mobile-toc-list');
});
</script>
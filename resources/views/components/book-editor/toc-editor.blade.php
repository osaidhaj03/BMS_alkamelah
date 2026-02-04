@props([
    'chapters' => collect(),
    'book' => null,
    'currentPage' => null
])

<style>
    .toc-editor-sidebar {
        width: 320px;
        height: calc(100vh - 80px);
        background-color: var(--bg-sidebar);
        border-left: 1px solid var(--border-color);
        overflow-y: auto;
        position: sticky;
        top: 0;
        transition: transform 0.3s ease;
    }
    
    @media (max-width: 1023px) {
        .toc-editor-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            z-index: 40;
            transform: translateX(100%);
            box-shadow: var(--shadow-dropdown);
        }
        
        .toc-editor-sidebar.open {
            transform: translateX(0);
        }
    }
    
    .toc-header {
        position: sticky;
        top: 0;
        background: var(--bg-sidebar);
        padding: 1rem;
        border-bottom: 2px solid var(--accent-color);
        z-index: 10;
    }
    
    .add-chapter-btn {
        width: 100%;
        padding: 0.75rem;
        background: var(--accent-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-family: var(--font-ui);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .add-chapter-btn:hover {
        background: var(--accent-hover);
        transform: translateY(-1px);
    }
</style>

<aside class="toc-editor-sidebar" id="toc-editor-sidebar">
    <!-- Header -->
    <div class="toc-header">
        <h3 class="text-lg font-bold mb-3" style="color: var(--text-main); font-family: var(--font-ui);">
            📑 إدارة الفهرس
        </h3>
        <button @click="$store.tocEditor.openAddModal(null)" class="add-chapter-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>إضافة فصل جديد</span>
        </button>
    </div>
    
    <!-- Chapters List -->
    <div class="p-4">
        @if($chapters && $chapters->count() > 0)
            @foreach($chapters as $chapter)
                <x-book-editor.chapter-item-editor 
                    :chapter="$chapter" 
                    :book="$book"
                    :currentPage="$currentPage"
                />
            @endforeach
        @else
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p style="font-family: var(--font-ui);">لا توجد فصول بعد</p>
                <p class="text-sm mt-1">ابدأ بإضافة فصل جديد</p>
            </div>
        @endif
</aside>

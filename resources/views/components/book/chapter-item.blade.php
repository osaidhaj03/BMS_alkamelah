{{-- Recursive Chapter Item Partial --}}
@props(['chapter', 'book' => null, 'currentPage' => null, 'level' => 0])

@php
    $isActive = $currentPage && $currentPage->chapter_id == $chapter->id;
    $hasChildren = $chapter->allChildren && $chapter->allChildren->isNotEmpty();
@endphp

<style>
    .toc-title {
        font-size: 0.9rem;
        line-height: 1.5rem;
    }
</style>

<li class="toc-item" data-chapter-id="{{ $chapter->id }}">
    <div class="flex items-start gap-1">
        {{-- Expand/Collapse Button (only if has children) --}}
        @if($hasChildren)
            <button type="button"
                class="expand-btn w-5 h-5 flex-shrink-0 flex items-center justify-center rounded hover:bg-gray-100 transition-colors text-gray-400 mt-2"
                onclick="toggleChapter({{ $chapter->id }})" aria-expanded="false">
                {{-- Arrow pointing LEFT (>) for RTL - rotates DOWN when expanded --}}
                <svg class="w-3 h-3 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
        @else
            <span class="w-5 flex-shrink-0"></span>
        @endif

        {{-- Chapter Link - Uses Livewire for smooth navigation --}}
        <button type="button"
           onclick="Livewire.dispatch('goToPage', { pageNumber: {{ $chapter->page_start ?? 1 }} })"
           class="flex-1 p-2 rounded-lg transition-colors block text-right w-full {{ $isActive ? '' : 'hover:bg-gray-50' }}"
           style="{{ $isActive ? 'background-color: var(--accent-light); color: var(--accent-color);' : 'color: var(--text-main);' }}"
           title="الانتقال للصفحة {{ $chapter->page_start ?? 1 }}">
            {{-- Title and page number on same line --}}
            <div class="flex justify-between items-start gap-2">
                <span class="toc-title flex-1 {{ $level > 0 ? 'text-gray-600' : 'font-medium' }}">
                    {{ $chapter->title }}
                </span>
                @if($chapter->page_start)
                    <span class="text-xs text-gray-400 flex-shrink-0 whitespace-nowrap">
                        {{ $chapter->page_start }}
                    </span>
                @endif
            </div>
        </button>
    </div>

    {{-- Children (Collapsible) --}}
    @if($hasChildren)
        <ul class="children-list mr-4 mt-1 space-y-0.5 border-r-2 border-green-100 pr-2 hidden"
            id="children-{{ $chapter->id }}">
            @foreach($chapter->allChildren as $child)
                <x-book.chapter-item :chapter="$child" :book="$book" :currentPage="$currentPage" :level="$level + 1" />
            @endforeach
        </ul>
    @endif
</li>
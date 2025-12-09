<x-superduper.main>

    <div class="page-wrapper relative z-[1]" dir="rtl">
        <main class="relative overflow-hidden main-wrapper">
            <!-- background pattern-->
            <div class="relative z-1">
                <div class="pattern-top top-0"></div>
                <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-1">
                    <div class="flex items-center gap-3 mb-8">
                        <img src="{{ asset('images/group0.svg') }}" alt="Icon" class="w-8 h-8">
                        <h2 class="text-4xl text-green-800 font-bold">
                            @if($currentSection)
                                كتب {{ $currentSection->name }}
                            @else
                                جميع الكتب
                            @endif
                        </h2>
                    </div>

                    @if($currentSection)
                        <div class="mb-8">
                            <a href="{{ route('show-all', ['type' => 'books']) }}" class="text-green-700 hover:text-green-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                العودة لجميع الكتب
                            </a>
                        </div>
                    @endif

                    {{-- استخدام Livewire Component للكتب --}}
                    @livewire('books-table', [
                        'showSearch' => true,
                        'showFilters' => true,
                        'title' => '',
                        'perPage' => 20,
                        'showPagination' => true,
                        'showPerPageSelector' => true,
                        'sectionSlug' => $currentSection?->slug
                    ])
                </section>
            </div>
        </main>
    </div>

</x-superduper.main>

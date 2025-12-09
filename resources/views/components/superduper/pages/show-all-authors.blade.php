<x-superduper.main>

    <div class="page-wrapper relative z-[1]" dir="rtl">
        <main class="relative overflow-hidden main-wrapper">
            <!-- background pattern-->
            <div class="relative z-1">
                <div class="pattern-top top-0"></div>
                <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-1">
                    <div class="flex items-center gap-3 mb-8">
                        <img src="{{ asset('images/group0.svg') }}" alt="Icon" class="w-8 h-8">
                        <h2 class="text-4xl text-green-800 font-bold">جميع المؤلفين</h2>
                    </div>

                    {{-- استخدام Livewire Component للمؤلفين --}}
                    @livewire('authors-table', [
                        'showSearch' => true,
                        'showFilters' => true,
                        'title' => '',
                        'perPage' => 20,
                        'showPagination' => true,
                        'showPerPageSelector' => true
                    ])
                </section>
            </div>
        </main>
    </div>

</x-superduper.main>

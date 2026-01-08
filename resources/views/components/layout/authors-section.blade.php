<!-- Authors Section -->
<div class="relative overflow-hidden bg-[#fafafa]" id="authors-section">
    <!-- Section Background Pattern -->
    <div class="absolute inset-0 pointer-events-none"
        style="background-image: url('{{ asset('assets/Frame 1321314420.png') }}'); background-repeat: repeat; background-size: 800px; background-attachment: fixed;">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20" dir="rtl">
        <div class="mb-12">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center">
                    <img src="{{ asset('images/group0.svg') }}" alt="Icon" class="w-full h-full object-contain">
                </div>
                <h2 class="text-3xl md:text-5xl font-extrabold text-[#1a3a2a]">أبرز المؤلفين</h2>
            </div>
        </div>

        <!-- Authors Table -->
        @livewire('authors-table', ['showSearch' => false, 'showFilters' => true, 'perPage' => 10])

        <!-- Action Button -->
        <div class="flex justify-center mt-8">
            <a href="{{ route('authors.index') }}"
                class="group relative px-10 py-4 bg-white border-2 border-[#2C6E4A] text-[#2C6E4A] rounded-full font-bold text-lg overflow-hidden transition-all hover:text-white">
                <div
                    class="absolute inset-0 bg-[#2C6E4A] translate-y-full group-hover:translate-y-0 transition-transform duration-300 -z-10">
                </div>
                <span>عرض جميع المؤلفين</span>
            </a>
        </div>
    </div>
</div>
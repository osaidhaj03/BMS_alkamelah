@php
    // الحصول على الإحصائيات بشكل ديناميكي
    $booksCount = \App\Models\Book::count();
    $authorsCount = \App\Models\Author::count();
    $pagesCount = \App\Models\Page::count();
    $sectionsCount = \App\Models\BookSection::where('is_active', true)->whereNull('parent_id')->count();
@endphp

<!-- Hero Section with Search -->
<div class="relative py-16 min-h-screen flex items-center" dir="rtl"
    style="background-image: url('{{ asset('images/الأقصى.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <!-- Dark overlay for better text readability -->
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">
        <!-- Main Title -->
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
            مكتبة تكاملت موضوعاتها و كتبها
        </h1>

        <!-- Description Paragraph -->
        <p class="text-xl md:text-2xl text-white mb-4 leading-relaxed font-medium max-w-4xl mx-auto">
            اكتشف <span class="text-white font-bold">{{ number_format($booksCount) }}</span> كتاباً في الحديث، الفقه،
            الأدب، البلاغة، والتاريخ والأنساب وغيرها الكثير
        </p>

        <p class="text-lg md:text-xl text-white mb-8 leading-relaxed max-w-4xl mx-auto">
            بأقلام <span class="text-white font-bold">{{ number_format($authorsCount) }}</span> مؤلف عبر <span
                class="text-white font-bold">{{ number_format($pagesCount) }}</span> صفحة موزعة على <span
                class="text-white font-bold">{{ $sectionsCount }}</span> قسم متخصص - كل ذلك متاح لك في مكان واحد
        </p>
    </div>
</div>
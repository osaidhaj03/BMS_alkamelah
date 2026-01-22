<style>
    .text-sm {
        font-size: 1rem;
        line-height: 0.35rem;
    }

    .border-b-2 {
        border-bottom-width: 2px;
    }

    .rounded-md {
        border-radius: -0.625rem;
    }

    .px-3 {
        padding-left: 0.05rem;
        padding-right: 0.05rem;
    }
</style>

<header class="bg-white shadow-sm border-b" dir="rtl" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/المكتبة الكاملة.png') }}" alt="المكتبة الكاملة" class="h-14 w-auto">
                </a>
            </div>

            <!-- Navigation - Centered -->
            <nav class="hidden md:flex space-x-8 space-x-reverse flex-1 justify-center">
                <a href="{{ route('home') }}"
                    class="text-gray-700 hover:text-green-800 px-3 py-2 text-sm font-medium border-b-2 border-transparent hover:border-green-800 transition-colors {{ request()->routeIs('home') ? 'border-green-800 text-green-800' : '' }}">
                    الرئيسية
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('search.static') }}"
                    class="text-gray-700 hover:text-green-800 px-3 py-2  text-sm font-medium border-b-2 border-transparent hover:border-green-800 transition-colors {{ request()->routeIs('search.static') ? 'border-green-800 text-green-800' : '' }}">
                    البحث
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('categories.index') }}"
                    class="text-gray-700 hover:text-green-800 px-3 py-2 text-sm font-medium border-b-2 border-transparent hover:border-green-800 transition-colors {{ request()->routeIs('categories.index') ? 'border-green-800 text-green-800' : '' }}">
                    الأقسام
                </a>

                <span class="text-gray-300">|</span>
                <a href="{{ route('books.index') }}"
                    class="text-gray-700 hover:text-green-800 px-3 py-2 text-sm font-medium border-b-2 border-transparent hover:border-green-800 transition-colors {{ request()->routeIs('books.index') ? 'border-green-800 text-green-800' : '' }}">
                    الكتب
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('authors.index') }}"
                    class="text-gray-700 hover:text-green-800 px-3 py-2  text-sm font-medium border-b-2 border-transparent hover:border-green-800 transition-colors {{ request()->routeIs('authors.index') ? 'border-green-800 text-green-800' : '' }}">
                    المؤلفين
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('about') }}"
                    class="text-gray-700 hover:text-green-800 px-3 py-2 text-sm font-medium border-b-2 border-transparent hover:border-green-800 transition-colors {{ request()->routeIs('about') ? 'border-green-800 text-green-800' : '' }}">
                    عن المكتبة
                </a>
            </nav>

            <!-- Feedback Button (Left Side) -->
            <div class="hidden md:flex items-center">
                <a href="/feedback"
                    class="px-5 py-2 border border-[#2C6E4A] text-[#2C6E4A] rounded-full text-sm font-bold hover:bg-[#2C6E4A] hover:text-white transition-colors duration-300">
                    رأيك يهمنا
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-gray-700 hover:text-green-800 focus:outline-none focus:text-green-800">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Container -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white border-t border-gray-100 py-4 shadow-lg text-right" 
         x-cloak>
        <div class="px-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:text-green-800 hover:bg-gray-50 rounded-lg">الرئيسية</a>
            <a href="{{ route('search.static') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:text-green-800 hover:bg-gray-50 rounded-lg">البحث</a>
            <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:text-green-800 hover:bg-gray-50 rounded-lg">الأقسام</a>
            <a href="{{ route('books.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:text-green-800 hover:bg-gray-50 rounded-lg">الكتب</a>
            <a href="{{ route('authors.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:text-green-800 hover:bg-gray-50 rounded-lg">المؤلفين</a>
            <a href="{{ route('about') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:text-green-800 hover:bg-gray-50 rounded-lg">عن المكتبة</a>
            
            <hr class="my-2 border-gray-100">
            
            <a href="/feedback" class="block px-4 py-3 text-center border-2 border-[#2C6E4A] text-[#2C6E4A] rounded-full font-bold hover:bg-[#2C6E4A] hover:text-white transition-colors">
                رأيك يهمنا
            </a>
        </div>
    </div>
</header>
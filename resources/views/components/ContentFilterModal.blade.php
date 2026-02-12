<!-- Filter Modal for Content Search -->
<div x-show="filterModalOpen && searchMode === 'content'" style="display: none;" x-cloak
    class="fixed inset-0 z-[9999] overflow-y-auto" aria-modal="true">

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm" @click="filterModalOpen = false"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div x-transition
            class="relative transform overflow-hidden rounded-xl bg-white text-right shadow-xl w-full max-w-lg flex flex-col max-h-[80vh]">

            <!-- Header -->
            <div class="bg-white px-6 pt-5 pb-4 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">تصفية المحتوى</h3>
                    <button @click="filterModalOpen = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex border-b border-gray-200">
                    <button @click="booksFilterTab = 'sections'"
                        class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors"
                        :class="booksFilterTab === 'sections' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500'">
                        الأقسام
                        <span x-show="sectionFilters.length > 0"
                            class="mr-1 text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full"
                            x-text="sectionFilters.length"></span>
                    </button>
                    <button @click="booksFilterTab = 'authors'"
                        class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors"
                        :class="booksFilterTab === 'authors' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500'">
                        المؤلفين
                        <span x-show="authorFilters.length > 0"
                            class="mr-1 text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full"
                            x-text="authorFilters.length"></span>
                    </button>
                    <button @click="booksFilterTab = 'books'"
                        class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors"
                        :class="booksFilterTab === 'books' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500'">
                        الكتب
                        <span x-show="bookFilters.length > 0"
                            class="mr-1 text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full"
                            x-text="bookFilters.length"></span>
                    </button>
                    <button @click="booksFilterTab = 'madhhab'"
                        class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors"
                        :class="booksFilterTab === 'madhhab' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500'">
                        المذهب
                        <span x-show="bookMadhhabFilters.length > 0"
                            class="mr-1 text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full"
                            x-text="bookMadhhabFilters.length"></span>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50 max-h-72">
                <!-- Sections Tab -->
                <div x-show="booksFilterTab === 'sections'">
                    <div class="mb-3">
                        <input type="text" x-model="sectionSearch" @input.debounce.300ms="fetchSections()"
                            placeholder="بحث في الأقسام..." class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <ul class="space-y-1">
                        <template x-for="section in sections" :key="section.id">
                            <li class="flex items-center py-2 px-4 hover:bg-white rounded-lg cursor-pointer"
                                @click="toggleFilter('section', section.id)">
                                <div class="flex-1 font-medium" x-text="section.name" style="font-size: 1rem;">
                                </div>
                                <div class="w-5 h-5 border rounded flex items-center justify-center"
                                    :class="sectionFilters.includes(section.id) ? 'bg-green-600 border-green-600' : 'border-gray-300'">
                                    <svg x-show="sectionFilters.includes(section.id)" class="w-3.5 h-3.5 text-white"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Authors Tab -->
                <div x-show="booksFilterTab === 'authors'">
                    <div class="mb-3">
                        <input type="text" x-model="authorSearch" @input.debounce.300ms="fetchAuthorsForFilter()"
                            placeholder="بحث في المؤلفين..."
                            class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <ul class="space-y-1">
                        <template x-for="author in authorsForFilter" :key="author.id">
                            <li class="flex items-center py-2 px-4 hover:bg-white rounded-lg cursor-pointer"
                                @click="toggleFilter('author', author.id)">
                                <div class="flex-1 font-medium" x-text="author.name" style="font-size: 1rem;"></div>
                                <div class="w-5 h-5 border rounded flex items-center justify-center"
                                    :class="authorFilters.includes(author.id) ? 'bg-green-600 border-green-600' : 'border-gray-300'">
                                    <svg x-show="authorFilters.includes(author.id)" class="w-3.5 h-3.5 text-white"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                    <!-- Load More Authors -->
                    <div x-show="hasMoreAuthors" class="mt-4 text-center">
                        <button @click="loadMoreAuthors()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200 transition-colors"
                            :disabled="loadingMoreAuthors">
                            <span x-show="!loadingMoreAuthors">عرض المزيد</span>
                            <span x-show="loadingMoreAuthors">جاري التحميل...</span>
                        </button>
                    </div>
                </div>

                <!-- Books Filter Tab -->
                <div x-show="booksFilterTab === 'books'">
                    <div class="mb-3">
                        <input type="text" x-model="bookSearch" @input.debounce.300ms="fetchBooksForFilter()"
                            placeholder="بحث في الكتب..."
                            class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <ul class="space-y-1">
                        <template x-for="book in booksForFilter" :key="book.id">
                            <li class="flex items-center py-2 px-4 hover:bg-white rounded-lg cursor-pointer"
                                @click="toggleFilter('book', book.id)">
                                <div class="flex-1 font-medium" x-text="book.name" style="font-size: 1rem;"></div>
                                <div class="w-5 h-5 border rounded flex items-center justify-center"
                                    :class="bookFilters.includes(book.id) ? 'bg-green-600 border-green-600' : 'border-gray-300'">
                                    <svg x-show="bookFilters.includes(book.id)" class="w-3.5 h-3.5 text-white"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                    <!-- Load More Books -->
                    <div x-show="hasMoreBooksForFilter" class="mt-4 text-center">
                        <button @click="loadMoreBooks()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200 transition-colors"
                            :disabled="loadingMoreBooksForFilter">
                            <span x-show="!loadingMoreBooksForFilter">عرض المزيد من الكتب</span>
                            <span x-show="loadingMoreBooksForFilter">جاري التحميل...</span>
                        </button>
                    </div>
                </div>

                <!-- Madhhab Filter Tab -->
                <div x-show="booksFilterTab === 'madhhab'">
                    <ul class="space-y-2">
                        <template x-for="m in ['المذهب الحنفي', 'المذهب المالكي', 'المذهب الشافعي', 'المذهب الحنبلي']" :key="m">
                            <li class="flex items-center py-3 px-4 hover:bg-white rounded-lg cursor-pointer"
                                @click="toggleBookMadhhabFilter(m)">
                                <div class="flex-1 font-medium" x-text="m"
                                    style="font-size: 1rem; line-height: 1.5rem;"></div>
                                <div class="w-5 h-5 border rounded flex items-center justify-center"
                                    :class="bookMadhhabFilters.includes(m) ? 'bg-green-600 border-green-600' : 'border-gray-300'">
                                    <svg x-show="bookMadhhabFilters.includes(m)" class="w-3.5 h-3.5 text-white"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-white px-6 py-3 gap-3 flex flex-row-reverse border-t border-gray-100">
                <button @click="filterModalOpen = false"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-500">تطبيق</button>
                <button @click="filterModalOpen = false"
                    class="px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold ring-1 ring-gray-300 hover:bg-gray-50">إلغاء</button>
                <button @click="clearBooksFilters()" class="mr-auto text-sm text-gray-500 hover:text-red-600">مسح
                    الكل</button>
            </div>
        </div>
    </div>
</div>

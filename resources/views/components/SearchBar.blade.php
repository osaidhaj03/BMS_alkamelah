<!-- Search Bar Component -->
<div class="w-full relative">
    <div
        class="relative flex items-center shadow-md transition-all duration-200 border border-gray-200 rounded-full bg-white overflow-visible h-12 md:h-14 z-30 focus-within:border-[#2C6E4A] focus-within:ring-1 focus-within:ring-[#2C6E4A]">

        <!-- Search Icon (Right) -->
        <div class="pl-3 pr-4" style="color: #BA4749;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Input -->
        <input type="text" x-model="query" @input.debounce.300ms="fetchSuggestions()"
            @focus="showDropdown = true" @click.outside="showDropdown = false"
            class="w-full h-full border-none focus:ring-0 text-lg text-gray-700 placeholder-gray-400 px-0 bg-transparent rounded-full text-right"
            :placeholder="placeholderText" dir="rtl">

        <!-- Actions (Left) -->
        <div class="flex items-center pl-2 gap-1 h-full">

            <!-- Filter Button -->
            <button @click="filterModalOpen = true"
                class="p-2 mr-1 rounded-full hover:bg-gray-100 transition-colors" style="color: #2C6E4A;"
                title="تصفية النتائج">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                <span x-show="getActiveFiltersCount() > 0"
                    class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center"
                    x-text="getActiveFiltersCount()"></span>
            </button>

            <!-- Settings Button (Visible only for Content Search) -->
            <div class="relative h-full flex items-center" x-show="searchMode === 'content'" x-cloak
                style="display: none;">
                <button @click="settingsOpen = !settingsOpen" @click.outside="settingsOpen = false"
                    class="p-2 ml-2 rounded-full hover:bg-gray-100 transition-colors"
                    :class="{'bg-gray-100': settingsOpen}" style="color: #2C6E4A;" title="إعدادات البحث">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>

                <!-- Settings Dropdown -->
                <div x-show="settingsOpen" x-transition x-cloak style="display: none;"
                    class="absolute top-full left-0 mt-4 w-[300px] sm:w-[400px] bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden text-right">
                    <div class="p-4 grid grid-cols-1 gap-4 text-right">
                        <!-- Search Type -->
                        <div class="space-y-2">
                            <h4
                                class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-100 pb-2">
                                نوع البحث</h4>
                            <div class="flex flex-col gap-1">
                                <label
                                    class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="searchType" value="exact_match" x-model="searchType"
                                        class="h-4 w-4" style="color: #2C6E4A;">
                                    <span class="text-sm font-medium">البحث المطابق</span>
                                </label>
                                <label
                                    class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="searchType" value="flexible_match" x-model="searchType"
                                        class="h-4 w-4" style="color: #2C6E4A;">
                                    <span class="text-sm font-medium">البحث المرن</span>
                                </label>
                            </div>
                        </div>
                        <!-- Word Order -->
                        <div class="space-y-2">
                            <h4
                                class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-100 pb-2">
                                ترتيب الكلمات</h4>
                            <div class="flex flex-col gap-1">
                                <label
                                    class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="wordOrder" value="consecutive" x-model="wordOrder"
                                        class="h-4 w-4" style="color: #2C6E4A;">
                                    <span class="text-sm font-medium">كلمات متتالية</span>
                                </label>
                                <label
                                    class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="wordOrder" value="any_order" x-model="wordOrder"
                                        class="h-4 w-4" style="color: #2C6E4A;">
                                    <span class="text-sm font-medium">أي ترتيب</span>
                                </label>
                            </div>
                        </div>
                        <!-- Word Match -->
                        <div class="space-y-2">
                            <h4
                                class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-100 pb-2">
                                شرط الكلمات</h4>
                            <div class="flex flex-col gap-1">
                                <label
                                    class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="wordMatch" value="all_words" x-model="wordMatch"
                                        class="h-4 w-4" style="color: #2C6E4A;">
                                    <span class="text-sm font-medium">كل الكلمات (AND)</span>
                                </label>
                                <label
                                    class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="wordMatch" value="some_words" x-model="wordMatch"
                                        class="h-4 w-4" style="color: #2C6E4A;">
                                    <span class="text-sm font-medium">بعض الكلمات (OR)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suggestions Dropdown -->
    <div x-show="showDropdown && suggestions.length > 0 && (searchMode === 'books' || searchMode === 'authors')"
        x-transition x-cloak style="display: none;"
        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden max-h-80 overflow-y-auto">
        <template x-for="item in suggestions" :key="item.id">
            <a :href="getSuggestionUrl(item)"
                class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors text-right">
                <div class="font-medium text-gray-900" x-text="item.name || item.title"></div>
                <div x-show="item.extra" class="text-sm text-gray-500" x-text="item.extra"></div>
            </a>
        </template>
        <div x-show="loadingSuggestions" class="px-4 py-3 text-center text-gray-500">
            <svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                </path>
            </svg>
        </div>
    </div>

    <!-- Quick Action Buttons (Mode Toggles) -->
    <div class="mt-8 flex justify-center gap-3">
        <button @click="searchMode = 'books'"
            class="px-6 py-2.5 font-bold rounded-md transition-all shadow-sm hover:shadow-md border border-[#2C6E4A]"
            :class="searchMode === 'books' ? 'bg-[#2C6E4A] text-white' : 'bg-white text-[#2C6E4A]'"
            style="font-size: 1rem; line-height: 1.35rem;">
            بحث في الكتب
        </button>
        <button @click="searchMode = 'authors'"
            class="px-6 py-2.5 font-bold rounded-md transition-all shadow-sm hover:shadow-md border border-[#2C6E4A]"
            :class="searchMode === 'authors' ? 'bg-[#2C6E4A] text-white' : 'bg-white text-[#2C6E4A]'"
            style="font-size: 1rem; line-height: 1.35rem;">
            بحث في المؤلفين
        </button>
        <button @click="searchMode = 'content'"
            class="px-6 py-2.5 font-bold rounded-md transition-all shadow-sm hover:shadow-md border border-[#2C6E4A]"
            :class="searchMode === 'content' ? 'bg-[#2C6E4A] text-white' : 'bg-white text-[#2C6E4A]'"
            style="font-size: 1rem; line-height: 1.35rem;">
            بحث في المحتوى
        </button>
    </div>
</div>

@extends('layouts.app')

@section('title', 'بحث')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 relative" dir="rtl"
     style="background-color: #fffdfa;"
     x-data="{ 
        query: '',
        settingsOpen: false,
        filterModalOpen: false,
        searchType: 'flexible',
        wordOrder: 'any',
        searchMode: 'books', // books, authors, content
        
        // Filter Data
        activeTab: 'sections',
        filterSearch: '',
        selectedItems: { books: [], authors: [], sections: [] },
        
        // Author Filter Data
        authorFilter: {
            yearStart: '',
            yearEnd: '',
            mazhab: ''
        },
        
        get placeholderText() {
            if (this.searchMode === 'books') return 'بحث في الكتب...';
            if (this.searchMode === 'authors') return 'بحث في المؤلفين...';
            return 'بحث في محتوى الكتب...';
        },

        mockData: {
            books: [
                { id: 1, name: 'تاريخ الأدب العربي' },
                { id: 2, name: 'مقدمة ابن خلدون' },
                { id: 3, name: 'ألف ليلة وليلة' },
                { id: 4, name: 'روايات نجيب محفوظ' },
                { id: 5, name: 'الحسب تمل مما' },
                { id: 6, name: 'العجيرة والندق' },
                { id: 7, name: 'المرضخة نجيب العربي' },
                { id: 8, name: 'كتاب سيبويه' },
                { id: 9, name: 'البخلاء للجاحظ' },
            ],
            authors: [
                { id: 1, name: 'طه حسين' },
                { id: 2, name: 'نزار قباني' },
                { id: 3, name: 'جبران خليل جبران' },
                { id: 4, name: 'محمود درويش' },
                { id: 5, name: 'المتنبي' },
            ],
            sections: [
                { id: 1, name: 'الأدب' },
                { id: 2, name: 'التاريخ' },
                { id: 3, name: 'الفلسفة' },
                { id: 4, name: 'العلوم الإسلامية' },
                { id: 5, name: 'اللغة العربية' },
            ]
        }
     }">
    
    <div class="w-full max-w-2xl z-10 flex flex-col items-center gap-8 -mt-20">
        
        <!-- Large Logo -->
        <div class="flex flex-col items-center gap-4">
            <img src="{{ asset('images/المكتبة الكاملة.png') }}" alt="المكتبة الكاملة" class="h-24 md:h-32 w-auto">
            <h1 class="sr-only">المكتبة الكاملة</h1>
        </div>

        <!-- Search Container -->
        <div class="w-full relative">
            <div class="relative flex items-center shadow-md transition-all duration-200 border border-gray-200 rounded-full bg-white overflow-visible h-12 md:h-14 z-30 focus-within:border-[#2C6E4A] focus-within:ring-1 focus-within:ring-[#2C6E4A]">
                
                <!-- Search Icon (Right) -->
                <div class="pl-3 pr-4" style="color: #BA4749;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Input -->
                <input type="text" 
                       x-model="query"
                       class="w-full h-full border-none focus:ring-0 text-lg text-gray-700 placeholder-gray-400 px-0 bg-transparent rounded-full"
                       :placeholder="placeholderText"
                       autofocus>

                <!-- Actions (Left) -->
                <div class="flex items-center pl-2 gap-1 h-full">
                    
                    <!-- Filter Button -->
                    <button @click="filterModalOpen = true" 
                            class="p-2 mr-1 rounded-full hover:bg-gray-100 transition-colors tooltip-trigger"
                            style="color: #2C6E4A;"
                            title="تصفية النتائج">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </button>

                    <!-- Settings Button (Visible only for Content Search) -->
                    <div class="relative h-full flex items-center" x-show="searchMode === 'content'">
                        <button @click="settingsOpen = !settingsOpen" 
                                @click.outside="settingsOpen = false"
                                class="p-2 ml-2 rounded-full hover:bg-gray-100 transition-colors"
                                :class="{'bg-gray-100': settingsOpen}"
                                style="color: #2C6E4A;"
                                title="إعدادات البحث">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </button>

                        <!-- Settings Dropdown -->
                        <div x-show="settingsOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute top-full left-0 mt-4 w-[300px] sm:w-[400px] bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden text-right">
                            <div class="p-4 grid grid-cols-1 gap-4 text-right">
                                <!-- Search Type -->
                                <div class="space-y-2">
                                     <h4 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-100 pb-2">نوع البحث</h4>
                                    <div class="flex flex-col gap-1">
                                        <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer transition-colors group">
                                            <input type="radio" name="searchType" value="exact" x-model="searchType" class="h-4 w-4 focus:ring-green-500 border-gray-300" style="color: #2C6E4A;">
                                            <span class="text-sm text-gray-700 group-hover:text-green-700 font-medium">البحث المطابق</span>
                                        </label>
                                        <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer transition-colors group">
                                            <input type="radio" name="searchType" value="flexible" x-model="searchType" class="h-4 w-4 focus:ring-green-500 border-gray-300" style="color: #2C6E4A;">
                                            <span class="text-sm text-gray-700 group-hover:text-green-700 font-medium">البحث الغير مطابق</span>
                                        </label>
                                        <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer transition-colors group">
                                            <input type="radio" name="searchType" value="morphological" x-model="searchType" class="h-4 w-4 focus:ring-green-500 border-gray-300" style="color: #2C6E4A;">
                                            <span class="text-sm text-gray-700 group-hover:text-green-700 font-medium">البحث الصرفي</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Word Order -->
                                <div class="space-y-2">
                                     <h4 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-100 pb-2">ترتيب الكلمات</h4>
                                    <div class="flex flex-col gap-1">
                                        <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer transition-colors group">
                                            <input type="radio" name="wordOrder" value="consecutive" x-model="wordOrder" class="h-4 w-4 focus:ring-blue-500 border-gray-300" style="color: #2C6E4A;">
                                            <span class="text-sm text-gray-700 group-hover:text-blue-700 font-medium">كلمات متتالية</span>
                                        </label>
                                        <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer transition-colors group">
                                            <input type="radio" name="wordOrder" value="paragraph" x-model="wordOrder" class="h-4 w-4 focus:ring-blue-500 border-gray-300" style="color: #2C6E4A;">
                                            <span class="text-sm text-gray-700 group-hover:text-blue-700 font-medium">في نفس الفقرة</span>
                                        </label>
                                        <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer transition-colors group">
                                            <input type="radio" name="wordOrder" value="any" x-model="wordOrder" class="h-4 w-4 focus:ring-blue-500 border-gray-300" style="color: #2C6E4A;">
                                            <span class="text-sm text-gray-700 group-hover:text-blue-700 font-medium">أي ترتيب</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Action Buttons (Mode Toggles) -->
            <div class="mt-8 flex justify-center gap-3">
                <button @click="searchMode = 'books'; activeTab = 'sections'"
                        class="px-6 py-2.5 text-sm font-bold rounded-md transition-all shadow-sm hover:shadow-md border border-[#2C6E4A]"
                        :class="searchMode === 'books' ? 'bg-[#2C6E4A] text-white' : 'bg-white text-[#2C6E4A]'">
                    بحث في الكتب
                </button>
                <button @click="searchMode = 'authors'"
                        class="px-6 py-2.5 text-sm font-bold rounded-md transition-all shadow-sm hover:shadow-md border border-[#2C6E4A]"
                        :class="searchMode === 'authors' ? 'bg-[#2C6E4A] text-white' : 'bg-white text-[#2C6E4A]'">
                    بحث في المؤلفين
                </button>
                <button @click="searchMode = 'content'; activeTab = 'books'"
                        class="px-6 py-2.5 text-sm font-bold rounded-md transition-all shadow-sm hover:shadow-md border border-[#2C6E4A]"
                        :class="searchMode === 'content' ? 'bg-[#2C6E4A] text-white' : 'bg-white text-[#2C6E4A]'">
                    بحث في محتوى الكتب
                </button>
            </div>
        </div>

    </div>

    <!-- Filter Modal -->
    <div x-show="filterModalOpen" 
         style="display: none;"
         class="fixed inset-0 z-[100] overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="filterModalOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity" 
             @click="filterModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="filterModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl flex flex-col max-h-[80vh]">
                
                <!-- Header -->
                <div class="bg-white px-[1.05rem] pt-5 pb-4 sm:px-[1.05rem] sm:pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">تصفية النتائج</h3>
                        <button @click="filterModalOpen = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Dynamic Tabs based on Mode -->
                    <div class="flex border-b border-gray-200" x-show="searchMode !== 'authors'">
                        <template x-for="tab in (searchMode === 'books' ? ['sections', 'authors'] : ['books', 'authors', 'sections'])">
                            <button @click="activeTab = tab"
                                    class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors duration-200"
                                    :class="activeTab === tab ? 'text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    :style="activeTab === tab ? 'border-color: #2C6E4A; color: #2C6E4A;' : ''">
                                <span x-text="tab === 'books' ? 'الكتب' : (tab === 'authors' ? 'المؤلفين' : 'الأقسام')"></span>
                                <span class="mr-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full" 
                                      x-show="selectedItems[tab].length > 0" 
                                      x-text="selectedItems[tab].length"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-1 overflow-hidden flex flex-col bg-gray-50">
                    
                    <!-- CASE: Authors Mode (Date & Mazhab) -->
                    <div x-show="searchMode === 'authors'" class="p-6 space-y-6">
                        <!-- Date Range -->
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-800 text-sm">تاريخ الولادة / الوفاة (هجري)</h4>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">من سنة</label>
                                    <input type="number" x-model="authorFilter.yearStart" class="w-full rounded-lg border-gray-300 focus:ring-[#2C6E4A] focus:border-[#2C6E4A]" placeholder="مثال: 100">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">إلى سنة</label>
                                    <input type="number" x-model="authorFilter.yearEnd" class="w-full rounded-lg border-gray-300 focus:ring-[#2C6E4A] focus:border-[#2C6E4A]" placeholder="مثال: 1400">
                                </div>
                            </div>
                        </div>

                        <!-- Mazhab -->
                        <div class="space-y-2">
                            <h4 class="font-bold text-gray-800 text-sm">المذهب</h4>
                            <select x-model="authorFilter.mazhab" class="w-full rounded-lg border-gray-300 focus:ring-[#2C6E4A] focus:border-[#2C6E4A]">
                                <option value="">الكل</option>
                                <option value="hanafi">حنفي</option>
                                <option value="maliki">مالكي</option>
                                <option value="shafi">شافعي</option>
                                <option value="hanbali">حنبلي</option>
                                <option value="zahir">ظاهري</option>
                                <option value="other">غير ذلك</option>
                            </select>
                        </div>
                    </div>

                    <!-- CASE: Standard Lists (Books/Content) -->
                    <div x-show="searchMode !== 'authors'" class="flex-1 flex flex-col overflow-hidden">
                        <!-- Inner Search -->
                        <div class="px-[1.05rem] py-4 bg-white border-b border-gray-100">
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       x-model="filterSearch" 
                                       class="block w-full rounded-lg border-gray-300 pr-10 focus:ring-green-500 text-[1rem] leading-[1.35rem] bg-gray-50" 
                                       :placeholder="'بحث في ' + (activeTab === 'books' ? 'الكتب' : (activeTab === 'authors' ? 'المؤلفين' : 'الأقسام')) + '...'"
                                       style="focus:border-color: #2C6E4A;">
                            </div>
                        </div>

                        <!-- List -->
                        <div class="flex-1 overflow-y-auto p-2">
                            <ul class="space-y-1">
                                <template x-for="item in mockData[activeTab].filter(i => i.name.includes(filterSearch))" :key="item.id">
                                    <li class="relative flex items-start py-2 px-[1.05rem] hover:bg-white hover:shadow-sm rounded-lg transition-all cursor-pointer"
                                        @click="
                                            if (selectedItems[activeTab].includes(item.id)) {
                                                selectedItems[activeTab] = selectedItems[activeTab].filter(id => id !== item.id);
                                            } else {
                                                selectedItems[activeTab].push(item.id);
                                            }
                                        ">
                                        <div class="min-w-0 flex-1 text-[1rem] leading-[1.35rem]">
                                            <label :for="'item-' + item.id" class="select-none font-medium text-gray-900 cursor-pointer" x-text="item.name"></label>
                                        </div>
                                        <div class="mr-3 flex h-6 items-center">
                                            <div class="relative flex items-center justify-center w-5 h-5 border rounded transition-colors"
                                                 :class="selectedItems[activeTab].includes(item.id) ? 'bg-green-600 border-green-600' : 'bg-white border-gray-300'"
                                                 :style="selectedItems[activeTab].includes(item.id) ? 'background-color: #2C6E4A; border-color: #2C6E4A;' : ''">
                                                 <svg x-show="selectedItems[activeTab].includes(item.id)" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                                <!-- Empty State -->
                                <li x-show="mockData[activeTab].filter(i => i.name.includes(filterSearch)).length === 0" class="py-8 text-center text-gray-500 text-sm">
                                    لا توجد نتائج مطابقة
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-white px-[1.05rem] py-3 gap-3 flex flex-row-reverse border-t border-gray-100">
                    <button type="button" 
                            class="inline-flex w-full justify-center rounded-lg px-[1.05rem] py-2 text-[1rem] leading-[1.35rem] font-semibold text-white shadow-sm sm:w-auto transition-opacity hover:opacity-90"
                            style="background-color: #2C6E4A;"
                            @click="filterModalOpen = false">
                        تطبيق
                    </button>
                    <button type="button" 
                            class="mt-0 inline-flex w-full justify-center rounded-lg bg-white px-[1.05rem] py-2 text-[1rem] leading-[1.35rem] font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto transition-colors"
                            @click="filterModalOpen = false">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer Links -->
    <div class="absolute bottom-0 w-full border-t border-gray-200 text-sm" 
         style="background-color: #2C6E4A; border-top-color: #BA4749;">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-center md:justify-between flex-wrap gap-4 text-white">
            <div class="flex gap-6 font-medium">
                <a href="#" class="hover:underline opacity-90 hover:opacity-100">عن المكتبة</a>
                <a href="#" class="hover:underline opacity-90 hover:opacity-100">المساعدة</a>
                <a href="#" class="hover:underline opacity-90 hover:opacity-100">سياسة الخصوصية</a>
            </div>
            <div class="flex gap-4 opacity-75">
                <span>الإصدار 1.0</span>
            </div>
        </div>
    </div>
</div>
@endsection

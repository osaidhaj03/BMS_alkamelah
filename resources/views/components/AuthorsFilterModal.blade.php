<!-- Filter Modal for Authors -->
<div x-show="filterModalOpen && searchMode === 'authors'" style="display: none;" x-cloak
    class="fixed inset-0 z-[9999] overflow-y-auto" aria-modal="true">

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm" @click="filterModalOpen = false"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div x-transition
            class="relative transform overflow-hidden rounded-xl bg-white text-right shadow-xl w-full max-w-lg flex flex-col max-h-[80vh]">

            <!-- Header -->
            <div class="bg-white px-6 pt-5 pb-4 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">تصفية المؤلفين</h3>
                    <button @click="filterModalOpen = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex border-b border-gray-200">
                    <button @click="authorsFilterTab = 'madhhab'"
                        class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors"
                        :class="authorsFilterTab === 'madhhab' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500'">
                        المذهب
                    </button>
                    <button @click="authorsFilterTab = 'century'"
                        class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors"
                        :class="authorsFilterTab === 'century' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500'">
                        القرن
                    </button>
                    <button @click="authorsFilterTab = 'daterange'"
                        class="flex-1 pb-3 text-sm font-bold text-center border-b-2 transition-colors"
                        :class="authorsFilterTab === 'daterange' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500'">
                        نطاق التاريخ
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50 max-h-72">
                <!-- Madhhab Tab -->
                <div x-show="authorsFilterTab === 'madhhab'">
                    <ul class="space-y-2">
                        <template x-for="m in availableMadhhabs" :key="m">
                            <li class="flex items-center py-3 px-4 hover:bg-white rounded-lg cursor-pointer"
                                @click="toggleMadhhabFilter(m)">
                                <div class="flex-1 font-medium" x-text="m"
                                    style="font-size: 1rem; line-height: 1.5rem;"></div>
                                <div class="w-5 h-5 border rounded flex items-center justify-center"
                                    :class="madhhabFilters.includes(m) ? 'bg-green-600 border-green-600' : 'border-gray-300'">
                                    <svg x-show="madhhabFilters.includes(m)" class="w-3.5 h-3.5 text-white"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Century Tab -->
                <div x-show="authorsFilterTab === 'century'">
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="(name, num) in availableCenturies" :key="num">
                            <button @click="toggleCenturyFilter(parseInt(num))"
                                class="py-4 px-2 text-center rounded-lg border-2 transition-all font-medium"
                                :class="centuryFilters.includes(parseInt(num)) ? 'bg-green-600 border-green-600 text-white' : 'bg-white border-gray-200 text-gray-700'"
                                x-text="name" style="font-size: 1rem; line-height: 1.5rem;">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Date Range Tab -->
                <div x-show="authorsFilterTab === 'daterange'">
                    <div class="bg-white rounded-lg p-5 shadow-sm">
                        <p class="text-gray-600 mb-5" style="font-size: 1rem; line-height: 1.5rem;">أدخل نطاق سنة
                            الوفاة بالتقويم الهجري:</p>
                        <div class="flex gap-4 items-center">
                            <div class="flex-1">
                                <label class="block font-medium text-gray-700 mb-2" style="font-size: 1rem;">من
                                    سنة</label>
                                <input type="number" x-model="deathDateFrom" placeholder="مثال: 150" min="1"
                                    max="1500" class="w-full px-4 py-3 rounded-lg border border-gray-300"
                                    style="font-size: 1rem;">
                            </div>
                            <span class="text-gray-400 pt-8 text-xl">—</span>
                            <div class="flex-1">
                                <label class="block font-medium text-gray-700 mb-2" style="font-size: 1rem;">إلى
                                    سنة</label>
                                <input type="number" x-model="deathDateTo" placeholder="مثال: 200" min="1"
                                    max="1500" class="w-full px-4 py-3 rounded-lg border border-gray-300"
                                    style="font-size: 1rem;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-white px-6 py-3 gap-3 flex flex-row-reverse border-t border-gray-100">
                <button @click="filterModalOpen = false"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-500">تطبيق</button>
                <button @click="filterModalOpen = false"
                    class="px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold ring-1 ring-gray-300 hover:bg-gray-50">إلغاء</button>
                <button @click="clearAuthorsFilters()" class="mr-auto text-sm text-gray-500 hover:text-red-600">مسح
                    الكل</button>
            </div>
        </div>
    </div>
</div>

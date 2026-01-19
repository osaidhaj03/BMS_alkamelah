<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4 text-right" dir="rtl"
    @if($isImporting) wire:poll.750ms="importBatch" @endif>
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h1 class="text-xl font-bold text-gray-800">استيراد كتاب من تراث</h1>
            <div class="p-2 bg-emerald-50 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Status Message -->
            @if($statusMessage)
                <div
                    class="p-4 rounded-md {{ Str::contains($statusMessage, 'فشل') || Str::contains($statusMessage, 'خطأ') ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700' }}">
                    {{ $statusMessage }}
                </div>
            @endif

            <!-- Input Form -->
            <div class="space-y-4">
                <div>
                    <label for="bookUrl" class="block text-sm font-medium text-gray-700 mb-1">رابط الكتاب أو
                        المعرف</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="text" wire:model="bookUrl" id="bookUrl"
                            class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            placeholder="https://app.turath.io/book/147927 أو 147927" {{ $isImporting ? 'disabled' : '' }}>
                    </div>
                    @error('bookUrl') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="sectionId" class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
                    <div class="relative rounded-md shadow-sm">
                        <select wire:model="sectionId" id="sectionId"
                            class="block w-full py-3 pr-4 pl-10 border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            {{ $isImporting ? 'disabled' : '' }}>
                            <option value="">-- اختر القسم --</option>
                            @foreach($sections as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="skipPages"
                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                            {{ $isImporting ? 'disabled' : '' }}>
                        <span class="mr-2 text-sm text-gray-600">استيراد الفهرس فقط</span>
                    </label>

                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="forceReimport"
                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                            {{ $isImporting ? 'disabled' : '' }}>
                        <span class="mr-2 text-sm text-gray-600">تحديث الكتاب الموجود</span>
                    </label>
                </div>
            </div>

            <!-- Book Details Preview -->
            @if($bookInfo)
                <div class="bg-emerald-50 rounded-lg p-5 border border-emerald-100 text-sm shadow-sm transition-all duration-300">
                    <h3 class="font-bold text-emerald-800 mb-4 border-b border-emerald-200 pb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        معاينة بيانات الكتاب
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
                        <div class="flex flex-col">
                            <span class="text-xs text-emerald-600 font-semibold mb-1">اسم الكتاب</span>
                            <span class="font-medium text-gray-900">{{ $bookInfo['meta']['name'] ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-xs text-emerald-600 font-semibold mb-1">المؤلف</span>
                            <span class="font-medium text-gray-900">{{ $parsedInfo['author_name'] ?? '-' }}</span>
                        </div>

                        @if(!empty($parsedInfo['editor_name']))
                            <div class="flex flex-col">
                                <span class="text-xs text-emerald-600 font-semibold mb-1">المحقق</span>
                                <span class="font-medium text-gray-900">{{ $parsedInfo['editor_name'] }}</span>
                            </div>
                        @endif

                        <div class="flex flex-col">
                            <span class="text-xs text-emerald-600 font-semibold mb-1">عدد المجلدات</span>
                            <span class="font-medium text-gray-900">
                                {{ isset($bookInfo['indexes']['volume_bounds']) ? count($bookInfo['indexes']['volume_bounds']) : '-' }}
                            </span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-xs text-emerald-600 font-semibold mb-1">إجمالي الصفحات</span>
                            <span class="font-medium text-gray-900">{{ $totalPages }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-xs text-emerald-600 font-semibold mb-1">المصدر</span>
                            <span class="font-medium text-gray-900">موقع تراث (Turath.io)</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-2">
                <button wire:click="startImport" {{ $isImporting ? 'disabled' : '' }}
                    class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="startImport">بدء الاستيراد</span>
                    <span wire:loading wire:target="startImport">جاري البدء...</span>
                </button>

                <button wire:click="previewBook" {{ $isImporting ? 'disabled' : '' }}
                    class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="previewBook">معاينة</span>
                    <span wire:loading wire:target="previewBook">جاري المعاينة...</span>
                </button>

                @if($isImporting)
                    <div class="mr-auto">
                        <span class="text-sm text-gray-500 italic">جاري الاستيراد الآن...</span>
                    </div>
                @endif
            </div>

            <!-- Progress Bar -->
            @if($progress > 0 || $isImporting)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>التقدم الإجمالي</span>
                        <span>{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-500 shadow-sm"
                            style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            @endif

            <!-- Operations Log -->
            @if(!empty($importLog))
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2 px-1">
                        <h3 class="text-sm font-semibold text-gray-700">سجل العمليات</h3>
                        <div class="flex gap-2">
                             <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                             <span class="text-[10px] text-gray-400 font-mono uppercase tracking-wider">Scraper Activity</span>
                        </div>
                    </div>
                    <div class="bg-gray-900 rounded-md p-4 font-mono text-xs h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-transparent border border-gray-700 shadow-inner"
                        id="log-container">
                        <div class="flex flex-col gap-1">
                            @foreach($importLog as $log)
                                <div
                                    class="text-gray-300 border-l-2 border-transparent pl-2 hover:border-emerald-500/30 hover:bg-emerald-900/10 py-0.5 rounded transition-colors duration-150">
                                    <span class="text-emerald-500/50 mr-2 select-none">#</span>
                                    {!! nl2br(e($log)) !!}
                                </div>
                            @endforeach
                            @if($isImporting)
                                <div class="text-emerald-500 animate-pulse mt-2 ml-1">▋</div>
                            @endif
                        </div>
                    </div>
                    <script>
                        // Auto-scroll to bottom directly without using Livewire hooks for simplicity
                        const logContainer = document.getElementById('log-container');
                        if (logContainer) {
                            const observer = new MutationObserver(() => {
                                logContainer.scrollTop = logContainer.scrollHeight;
                            });
                            observer.observe(logContainer, { childList: true, subtree: true });
                            logContainer.scrollTop = logContainer.scrollHeight; // Initial scroll
                        }
                    </script>
                </div>
            @endif
        </div>
    </div>
</div>
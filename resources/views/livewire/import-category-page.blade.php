<div class="min-h-screen bg-gray-100 flex flex-col items-center p-6" dir="rtl"
    @if($isImporting) wire:poll.1s="importNextBook" @elseif($isFetching) wire:poll.500ms @endif>
    <div class="w-full max-w-5xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🗂️ استيراد قسم كامل من تراث</h1>
            <p class="text-emerald-600">Turath.io Category Batch Importer</p>
        </div>

        <!-- Input Section -->
        <div class="bg-white rounded-2xl p-6 mb-6 border border-gray-200 shadow-sm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">📝 أدخل IDs الكتب (مفصولة بفاصلة أو سطر جديد)</label>
                    <textarea wire:model.live="manualBookIds" id="manualBookIds" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition resize-none"
                        placeholder="مثال: 6513, 6514, 6515 أو كل ID في سطر جديد"
                        {{ $isImporting || $isFetching ? 'disabled' : '' }}></textarea>
                    <p class="text-xs text-gray-500 mt-2">💡 افتح صفحة القسم في <a href="https://app.turath.io/categories" target="_blank" class="text-emerald-600 hover:underline">تراث</a> واستخرج IDs الكتب من الروابط</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">📂 القسم المحلي</label>
                        <select wire:model="sectionId" id="sectionId"
                            class="w-full py-3 px-4 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition"
                            {{ $isImporting ? 'disabled' : '' }}>
                            <option value="">-- اختر القسم --</option>
                            @foreach($sections as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <div class="flex flex-col gap-3">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="skipPages"
                                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    {{ $isImporting ? 'disabled' : '' }}>
                                <span class="mr-2 text-sm text-gray-600">استيراد الفهرس فقط (بدون الصفحات)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="forceReimport"
                                    class="rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-300 focus:ring focus:ring-amber-200 focus:ring-opacity-50"
                                    {{ $isImporting ? 'disabled' : '' }}>
                                <span class="mr-2 text-sm text-amber-700 font-medium">🔄 تحديث الكتاب الموجود (حذف وإعادة استيراد)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button wire:click="loadBooks" {{ $isImporting || $isFetching || empty($manualBookIds) ? 'disabled' : '' }}
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="loadBooks">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="loadBooks">جلب الكتب</span>
                        <span wire:loading wire:target="loadBooks">جاري الجلب...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Message -->
        @if($statusMessage)
            <div class="mb-6 p-4 rounded-xl {{ Str::contains($statusMessage, ['فشل', 'خطأ']) ? 'bg-red-50 text-red-700 border border-red-200' : (Str::contains($statusMessage, 'اكتمل') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                {{ $statusMessage }}
            </div>
        @endif

        <!-- Books Section -->
        @if(count($books) > 0)
            <!-- Books Table -->
            <div class="bg-white rounded-2xl p-6 mb-6 border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        📚 الكتب في القسم
                        <span class="text-sm bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">{{ count($books) }} كتاب</span>
                    </h2>
                    <div class="flex gap-2">
                        @if(!$isImporting)
                            <button wire:click="startImport"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition flex items-center gap-2">
                                ▶️ بدء الاستيراد
                            </button>
                        @else
                            <button wire:click="stopImport"
                                class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg transition flex items-center gap-2">
                                ⏸️ إيقاف
                            </button>
                        @endif
                        <button wire:click="resetForm"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition flex items-center gap-2">
                            🔄 إعادة تعيين
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto max-h-80 overflow-y-auto rounded-xl border border-gray-200">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">ID</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">اسم الكتاب</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">المؤلف</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">الصفحات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($books as $index => $book)
                                <tr class="hover:bg-gray-50 transition {{ $index === $currentBookIndex && $isImporting ? 'bg-amber-50' : '' }}">
                                    <td class="px-4 py-3">
                                        @if($book['status'] === 'pending')
                                            <span class="text-gray-400 text-lg">⏳</span>
                                        @elseif($book['status'] === 'importing')
                                            <span class="text-amber-500 text-lg animate-pulse">🔄</span>
                                        @elseif($book['status'] === 'done')
                                            <span class="text-emerald-500 text-lg">✅</span>
                                        @elseif($book['status'] === 'error')
                                            <span class="text-red-500 text-lg">❌</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $book['id'] }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $book['name'] }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $book['author'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $book['pages'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white rounded-2xl p-6 mb-6 border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-emerald-700 font-medium">التقدم</span>
                    <span class="text-sm text-gray-600">{{ $completedBooks + $failedBooks }} / {{ count($books) }}</span>
                </div>
                <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 transition-all duration-500 rounded-full"
                        style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex justify-between text-xs mt-2">
                    <div class="flex gap-4">
                        <span class="text-emerald-600">✅ {{ $completedBooks }} نجاح</span>
                        @if($failedBooks > 0)
                            <span class="text-red-600">❌ {{ $failedBooks }} فشل</span>
                        @endif
                    </div>
                    <span class="text-gray-500">{{ $progress }}%</span>
                </div>
            </div>
        @endif

        <!-- Operations Log -->
        @if(!empty($importLog))
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        📋 سجل العمليات
                        @if($isImporting || $isFetching)
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        @endif
                    </h3>
                    <span class="text-xs text-gray-400 font-mono uppercase tracking-wider">Activity Log</span>
                </div>
                <div class="bg-gray-900 rounded-xl p-4 font-mono text-xs h-64 overflow-y-auto" id="log-container"
                    style="scrollbar-width: thin; scrollbar-color: #10b981 transparent;">
                    <div class="flex flex-col gap-1">
                        @foreach($importLog as $log)
                            <div class="text-gray-300 border-r-2 border-transparent pr-2 hover:border-emerald-500/30 hover:bg-emerald-900/10 py-0.5 rounded transition-colors duration-150">
                                <span class="text-emerald-500/50 ml-2 select-none">#</span>
                                {!! nl2br(e($log)) !!}
                            </div>
                        @endforeach
                        @if($isImporting || $isFetching)
                            <div class="text-emerald-500 animate-pulse mt-2 mr-1">▋</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Auto-scroll log -->
    <script>
        document.addEventListener('livewire:update', function() {
            const logContainer = document.getElementById('log-container');
            if (logContainer) {
                logContainer.scrollTop = logContainer.scrollHeight;
            }
        });
        
        // Initial scroll
        const logContainer = document.getElementById('log-container');
        if (logContainer) {
            logContainer.scrollTop = logContainer.scrollHeight;
            
            const observer = new MutationObserver(() => {
                logContainer.scrollTop = logContainer.scrollHeight;
            });
            observer.observe(logContainer, { childList: true, subtree: true });
        }
    </script>
</div>
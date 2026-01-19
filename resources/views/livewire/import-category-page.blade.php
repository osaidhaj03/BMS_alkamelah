<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4 text-right" dir="rtl"
    @if($isImporting) wire:poll.1s="importNextBook" @endif>
    <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h1 class="text-xl font-bold text-gray-800">🗂️ استيراد قسم كامل من تراث</h1>
            <div class="p-2 bg-emerald-50 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Status Message -->
            @if($statusMessage)
                <div
                    class="p-4 rounded-md {{ Str::contains($statusMessage, 'فشل') || Str::contains($statusMessage, 'خطأ') ? 'bg-red-50 text-red-700' : (Str::contains($statusMessage, 'اكتمل') ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700') }}">
                    {{ $statusMessage }}
                </div>
            @endif

            <!-- Input Form -->
            <div class="space-y-4">
                <div>
                    <label for="manualBookIds" class="block text-sm font-medium text-gray-700 mb-1">
                        أدخل IDs الكتب (مفصولة بفاصلة أو سطر جديد)
                    </label>
                    <textarea wire:model="manualBookIds" id="manualBookIds" rows="4"
                        class="block w-full px-4 py-3 border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm resize-none"
                        placeholder="مثال: 1234, 1235, 1236 أو كل ID في سطر جديد" {{ $isImporting || $isFetching ? 'disabled' : '' }}></textarea>
                    <p class="text-xs text-gray-500 mt-1">💡 افتح صفحة القسم في تراث واستخرج IDs الكتب من الروابط</p>
                </div>

                <div>
                    <label for="sectionId" class="block text-sm font-medium text-gray-700 mb-1">القسم المحلي</label>
                    <select wire:model="sectionId" id="sectionId"
                        class="block w-full py-3 pr-4 pl-10 border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        {{ $isImporting ? 'disabled' : '' }}>
                        <option value="">-- اختر القسم --</option>
                        @foreach($sections as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="skipPages"
                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                            {{ $isImporting ? 'disabled' : '' }}>
                        <span class="mr-2 text-sm text-gray-600">استيراد الفهرس فقط (بدون الصفحات)</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-2">
                <button wire:click="loadBooks" {{ $isImporting || $isFetching || empty($manualBookIds) ? 'disabled' : '' }}
                    class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="loadBooks">📚 تحميل الكتب</span>
                    <span wire:loading wire:target="loadBooks">جاري التحميل...</span>
                </button>

                @if(count($books) > 0 && !$isImporting)
                    <button wire:click="startImport"
                        class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                        ▶️ بدء الاستيراد
                    </button>
                @endif

                @if($isImporting)
                    <button wire:click="stopImport"
                        class="px-6 py-2 bg-amber-600 text-white font-medium rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                        ⏸️ إيقاف
                    </button>
                @endif

                @if(count($books) > 0)
                    <button wire:click="resetForm"
                        class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        🔄 إعادة تعيين
                    </button>
                @endif
            </div>

            <!-- Books Table -->
            @if(count($books) > 0)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-100 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-700">📚 قائمة الكتب</h3>
                        <span class="text-sm bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">
                            {{ count($books) }} كتاب
                        </span>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-right font-medium text-gray-600">الحالة</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-600">ID</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-600">اسم الكتاب</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-600">المؤلف</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-600">الصفحات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($books as $index => $book)
                                    <tr
                                        class="hover:bg-gray-50 transition {{ $index === $currentBookIndex && $isImporting ? 'bg-amber-50' : '' }}">
                                        <td class="px-4 py-3">
                                            @if($book['status'] === 'pending')
                                                <span class="text-gray-400">⏳</span>
                                            @elseif($book['status'] === 'importing')
                                                <span class="text-amber-500 animate-pulse">🔄</span>
                                            @elseif($book['status'] === 'done')
                                                <span class="text-emerald-500">✅</span>
                                            @elseif($book['status'] === 'error')
                                                <span class="text-red-500">❌</span>
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
            @endif

            <!-- Progress Bar -->
            @if($isImporting || $progress > 0)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>التقدم: {{ $completedBooks + $failedBooks }} / {{ count($books) }}</span>
                        <span>{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-600 h-3 rounded-full transition-all duration-500 shadow-sm"
                            style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-1">
                        <span class="text-emerald-600">✅ {{ $completedBooks }} نجاح</span>
                        @if($failedBooks > 0)
                            <span class="text-red-600">❌ {{ $failedBooks }} فشل</span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Operations Log -->
            @if(!empty($importLog))
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2 px-1">
                        <h3 class="text-sm font-semibold text-gray-700">📋 سجل العمليات</h3>
                        <div class="flex gap-2">
                            @if($isImporting || $isFetching)
                                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            @endif
                            <span class="text-[10px] text-gray-400 font-mono uppercase tracking-wider">Activity Log</span>
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
                            @if($isImporting || $isFetching)
                                <div class="text-emerald-500 animate-pulse mt-2 ml-1">▋</div>
                            @endif
                        </div>
                    </div>
                    <script>
                        const logContainer = document.getElementById('log-container');
                        if (logContainer) {
                            const observer = new MutationObserver(() => {
                                logContainer.scrollTop = logContainer.scrollHeight;
                            });
                            observer.observe(logContainer, { childList: true, subtree: true });
                            logContainer.scrollTop = logContainer.scrollHeight;
                        }
                    </script>
                </div>
            @endif
        </div>
    </div>
</div>
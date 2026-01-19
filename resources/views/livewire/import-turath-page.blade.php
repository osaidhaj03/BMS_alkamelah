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
                        <span class="text-sm text-gray-500">جاري الاستيراد...</span>
                    </div>
                @endif
            </div>

            <!-- Progress Bar -->
            @if($progress > 0 || $isImporting)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>التقدم</span>
                        <span>{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            @endif

            <!-- Operations Log -->
            @if(!empty($importLog))
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-700">سجل العمليات</h3>
                        <span class="text-xs text-gray-400 font-mono">Terminal Output</span>
                    </div>
                    <div class="bg-gray-900 rounded-md p-4 font-mono text-xs h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-transparent border border-gray-700 shadow-inner"
                        id="log-container">
                        <div class="flex flex-col gap-1">
                            @foreach($importLog as $log)
                                <div
                                    class="text-gray-300 border-l-2 border-transparent pl-2 hover:border-gray-600 hover:bg-gray-800/50 py-0.5 rounded transition-colors duration-150">
                                    <span class="text-emerald-500 mr-2 select-none">$</span>
                                    {!! nl2br(e($log)) !!}
                                </div>
                            @endforeach
                            @if($isImporting)
                                <div class="text-emerald-500 animate-pulse mt-2">_</div>
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
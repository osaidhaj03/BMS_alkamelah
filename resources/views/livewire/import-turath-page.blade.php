<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4 text-right" dir="rtl"
    @if($batchMode && ($isImporting || $readyForNextBook))
        wire:poll.1s="checkBatchProgress"
    @elseif($isImporting)
        wire:poll.750ms="importBatch"
    @endif>
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h1 class="text-xl font-bold text-gray-800">استيراد من تراث</h1>
            <div class="p-2 bg-emerald-50 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Status Message -->
            @if($statusMessage)
                <div class="p-4 rounded-md {{ Str::contains($statusMessage, ['فشل', 'خطأ']) ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700' }}">
                    {{ $statusMessage }}
                </div>
            @endif

            <!-- Mode Toggle (only show when not importing) -->
            @if(!$isImporting && !$batchMode)
                <div class="flex items-center justify-center gap-4 bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <span class="text-sm text-gray-600">وضع الاستيراد:</span>
                    <div class="flex gap-2">
                        <button wire:click="$set('batchIdsText', '')" 
                            class="px-4 py-2 text-sm rounded-md {{ empty($batchIdsText) ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">
                            كتاب واحد
                        </button>
                        <button wire:click="$set('batchIdsText', '// أدخل IDs هنا')"
                            class="px-4 py-2 text-sm rounded-md {{ !empty($batchIdsText) ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">
                            كتب متعددة
                        </button>
                    </div>
                </div>
            @endif

            <!-- Single Book Mode -->
            @if(empty($batchIdsText) && !$batchMode)
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رابط الكتاب أو المعرف</label>
                        <input type="text" wire:model="bookUrl"
                            class="block w-full px-4 py-3 border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="https://app.turath.io/book/147927 أو 147927" {{ $isImporting ? 'disabled' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
                        <select wire:model="sectionId" class="block w-full py-3 px-4 border-gray-300 rounded-md">
                            <option value="">-- اختر القسم --</option>
                            @foreach($sections as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="skipPages" class="rounded border-gray-300 text-emerald-600">
                            <span class="mr-2 text-sm text-gray-600">استيراد الفهرس فقط</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="forceReimport" class="rounded border-gray-300 text-emerald-600">
                            <span class="mr-2 text-sm text-gray-600">تحديث الموجود</span>
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="startImport" {{ $isImporting ? 'disabled' : '' }}
                            class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-md hover:bg-emerald-700 disabled:opacity-50">
                            بدء الاستيراد
                        </button>
                        <button wire:click="previewBook" {{ $isImporting ? 'disabled' : '' }}
                            class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 disabled:opacity-50">
                            معاينة
                        </button>
                    </div>
                </div>

                @if($bookInfo)
                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-100 text-sm">
                        <h3 class="font-bold text-emerald-800 mb-3">معاينة الكتاب</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div><span class="text-emerald-600 text-xs">الاسم</span><br>{{ $bookInfo['meta']['name'] ?? '-' }}</div>
                            <div><span class="text-emerald-600 text-xs">المؤلف</span><br>{{ $parsedInfo['author_name'] ?? '-' }}</div>
                            <div><span class="text-emerald-600 text-xs">المجلدات</span><br>{{ isset($bookInfo['indexes']['volume_bounds']) ? count($bookInfo['indexes']['volume_bounds']) : '-' }}</div>
                            <div><span class="text-emerald-600 text-xs">الصفحات</span><br>{{ $totalPages }}</div>
                        </div>
                    </div>
                @endif

            @else
                <!-- Batch Mode -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            أرقام الكتب (ID) - كل رقم في سطر
                        </label>
                        <textarea wire:model="batchIdsText" rows="5"
                            class="block w-full px-4 py-3 border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 font-mono text-sm"
                            placeholder="147927
12216
151
8630"
                            {{ $isImporting || $batchMode ? 'disabled' : '' }}></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
                        <select wire:model="sectionId" class="block w-full py-3 px-4 border-gray-300 rounded-md" {{ $isImporting || $batchMode ? 'disabled' : '' }}>
                            <option value="">-- اختر القسم --</option>
                            @foreach($sections as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="skipPages" class="rounded border-gray-300 text-emerald-600" {{ $isImporting || $batchMode ? 'disabled' : '' }}>
                            <span class="mr-2 text-sm text-gray-600">استيراد الفهرس فقط</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="forceReimport" class="rounded border-gray-300 text-emerald-600" {{ $isImporting || $batchMode ? 'disabled' : '' }}>
                            <span class="mr-2 text-sm text-gray-600">تحديث الموجود</span>
                        </label>
                    </div>

                    <div class="flex gap-3 items-center">
                        @if(!$batchMode)
                            <button wire:click="startBatchImport"
                                class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-md hover:bg-emerald-700 disabled:opacity-50">
                                🚀 بدء استيراد الكتب
                            </button>
                        @else
                            <div class="flex items-center gap-2 text-emerald-600">
                                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="font-medium">جاري الاستيراد: {{ $batchCompleted + $batchFailed + 1 }}/{{ $batchTotal }}</span>
                            </div>
                        @endif
                        
                        <button wire:click="resetForm" {{ $isImporting ? 'disabled' : '' }}
                            class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 disabled:opacity-50">
                            إعادة تعيين
                        </button>
                    </div>

                    @if($batchMode)
                        <div class="flex items-center gap-4 text-sm bg-gray-50 rounded-lg p-3">
                            <span class="text-green-600 font-medium">✅ نجاح: {{ $batchCompleted }}</span>
                            <span class="text-red-600 font-medium">❌ فشل: {{ $batchFailed }}</span>
                            <span class="text-gray-500">📊 متبقي: {{ count($pendingIds) }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Progress Bar -->
            @if($progress > 0 || $isImporting)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>تقدم الكتاب الحالي</span>
                        <span>{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            @endif

            <!-- Log -->
            @if(!empty($importLog))
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-700">سجل العمليات</h3>
                        @if($isImporting || $readyForNextBook)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-xs text-gray-400">جاري</span>
                            </div>
                        @endif
                    </div>
                    <div class="bg-gray-900 rounded-md p-4 font-mono text-xs h-56 overflow-y-auto" id="log-box">
                        @foreach($importLog as $log)
                            <div class="text-gray-300 py-0.5">{!! nl2br(e($log)) !!}</div>
                        @endforeach
                        @if($isImporting || $readyForNextBook)
                            <div class="text-emerald-500 animate-pulse">▋</div>
                        @endif
                    </div>
                    <script>
                        const box = document.getElementById('log-box');
                        if(box) { new MutationObserver(() => box.scrollTop = box.scrollHeight).observe(box, {childList:true, subtree:true}); box.scrollTop = box.scrollHeight; }
                    </script>
                </div>
            @endif
        </div>
    </div>
</div>

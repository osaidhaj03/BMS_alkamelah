<x-filament-panels::page>
    {{-- نموذج إدخال الرابط --}}
    <x-filament-panels::form wire:submit.prevent="startImport">
        {{ $this->form }}
    </x-filament-panels::form>

    {{-- معلومات الكتاب (بعد المعاينة) --}}
    @if($this->bookInfo)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-book-open class="h-5 w-5 text-primary-500" />
                    <span>معلومات الكتاب</span>
                </div>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">اسم الكتاب</p>
                    <p class="font-semibold text-lg">{{ $this->bookInfo['meta']['name'] ?? 'غير معروف' }}</p>
                </div>

                @if($this->parsedInfo['author_name'] ?? null)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">المؤلف</p>
                        <p class="font-semibold">{{ $this->parsedInfo['author_name'] }}</p>
                    </div>
                @endif

                @if($this->parsedInfo['editor_name'] ?? null)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">المحقق</p>
                        <p class="font-semibold">{{ $this->parsedInfo['editor_name'] }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">عدد الصفحات</p>
                    <p class="font-semibold text-primary-600">{{ $this->totalPages }} صفحة</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">المجلدات</p>
                    <p class="font-semibold">{{ count($this->bookInfo['indexes']['volumes'] ?? [1]) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">الفصول</p>
                    <p class="font-semibold">{{ count($this->bookInfo['indexes']['headings'] ?? []) }}</p>
                </div>
            </div>
        </x-filament::section>
    @endif

    {{-- شريط التقدم --}}
    @if($this->isImporting || $this->progress > 0)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    @if($this->isImporting)
                        <x-filament::loading-indicator class="h-5 w-5" />
                    @else
                        <x-heroicon-o-check-circle class="h-5 w-5 text-success-500" />
                    @endif
                    <span>حالة الاستيراد</span>
                </div>
            </x-slot>

            <div class="space-y-4">
                {{-- شريط التقدم --}}
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            التقدم
                        </span>
                        <span class="text-sm font-medium text-primary-600 dark:text-primary-400">
                            {{ $this->progress }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-4 rounded-full transition-all duration-500 ease-out flex items-center justify-center"
                            style="width: {{ $this->progress }}%">
                            @if($this->progress > 10)
                                <span class="text-xs text-white font-medium">{{ $this->progress }}%</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- إحصائيات --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-primary-600">{{ $this->importedPages }}</p>
                        <p class="text-xs text-gray-500">صفحة مستوردة</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-gray-600">{{ $this->totalPages }}</p>
                        <p class="text-xs text-gray-500">إجمالي الصفحات</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-gray-600">{{ $this->totalPages - $this->importedPages }}</p>
                        <p class="text-xs text-gray-500">المتبقي</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold {{ $this->isImporting ? 'text-warning-500' : 'text-success-500' }}">
                            {{ $this->isImporting ? 'جاري...' : 'مكتمل' }}
                        </p>
                        <p class="text-xs text-gray-500">الحالة</p>
                    </div>
                </div>

                {{-- رسالة الحالة --}}
                @if($this->statusMessage)
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::loading-indicator class="h-4 w-4" />
                        <span>{{ $this->statusMessage }}</span>
                    </div>
                @endif

                {{-- زر الإلغاء --}}
                @if($this->isImporting)
                    <div class="flex justify-end">
                        <x-filament::button wire:click="cancelImport" color="danger" size="sm">
                            <x-heroicon-o-x-circle class="h-4 w-4 me-1" />
                            إلغاء الاستيراد
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </x-filament::section>
    @endif

    {{-- سجل العمليات --}}
    @if(count($this->importLog) > 0)
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-document-text class="h-5 w-5" />
                    <span>سجل العمليات</span>
                    <span class="text-xs text-gray-400">({{ count($this->importLog) }} سجل)</span>
                </div>
            </x-slot>

            <div class="bg-gray-900 rounded-lg p-4 font-mono text-sm max-h-80 overflow-y-auto" dir="ltr">
                @foreach($this->importLog as $log)
                    <div class="flex gap-2 {{ $loop->last ? '' : 'mb-1' }}">
                        <span class="text-gray-500 shrink-0">[{{ $log['time'] }}]</span>
                        <span class="text-gray-100">{{ $log['message'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @endif

    {{-- تعليمات الاستخدام --}}
    @if(!$this->bookInfo && !$this->isImporting && count($this->importLog) === 0)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-information-circle class="h-5 w-5 text-info-500" />
                    <span>تعليمات الاستخدام</span>
                </div>
            </x-slot>

            <div class="prose dark:prose-invert max-w-none text-sm">
                <ol class="space-y-2">
                    <li>
                        <strong>أدخل رابط الكتاب:</strong>
                        يمكنك إدخال الرابط الكامل مثل:
                        <code
                            class="bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">https://app.turath.io/book/147927</code>
                        أو معرف الكتاب فقط:
                        <code class="bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">147927</code>
                    </li>
                    <li>
                        <strong>اضغط "معاينة":</strong>
                        لعرض معلومات الكتاب قبل بدء الاستيراد
                    </li>
                    <li>
                        <strong>اضغط "بدء الاستيراد":</strong>
                        لبدء عملية الاستيراد
                    </li>
                </ol>

                <div
                    class="mt-4 p-3 bg-warning-50 dark:bg-warning-900/20 rounded-lg border border-warning-200 dark:border-warning-800">
                    <p class="text-warning-700 dark:text-warning-400 flex items-start gap-2">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 shrink-0 mt-0.5" />
                        <span>
                            <strong>ملاحظة:</strong>
                            قد يستغرق استيراد الكتب الكبيرة (أكثر من 500 صفحة) عدة دقائق.
                            الرجاء عدم إغلاق الصفحة أثناء الاستيراد.
                        </span>
                    </p>
                </div>
            </div>
        </x-filament::section>
    @endif

    {{-- تحديث تلقائي أثناء الاستيراد --}}
    @if($this->isImporting)
        <script>
            // تحديث الصفحة كل 2 ثانية أثناء الاستيراد
            setTimeout(() => {
                if (@json($this->isImporting)) {
                    window.Livewire.dispatch('$refresh');
                }
            }, 2000);
        </script>
    @endif
</x-filament-panels::page>
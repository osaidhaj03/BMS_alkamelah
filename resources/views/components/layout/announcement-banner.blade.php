<div x-data="{ show: true }" 
     x-show="show" 
     class="bg-[#1a3a2a] text-white py-2 px-4 relative z-[10000]" 
     dir="rtl"
     x-cloak>
    <div class="max-w-7xl mx-auto flex items-center justify-center text-center">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-900/50 border border-green-700/50 uppercase tracking-wider text-green-300">
                إصدار تجريبي
            </span>
            <p class="text-xs md:text-sm font-medium leading-relaxed">
                أهلاً بك في الإصدار التجريبي الأول من المكتبة الكاملة - نسعد بملاحظاتكم لتطوير التجربة.
                <a href="/feedback" class="mr-3 px-2 py-0.5 bg-white text-[#1a3a2a] rounded text-[10px] font-bold hover:bg-green-100 transition-colors">
                    لملاحظاتكم
                </a>
            </p>
        </div>
        
        <!-- Close Button -->
        <button @click="show = false" class="absolute left-4 p-1 hover:bg-white/10 rounded-full transition-colors text-white/70 hover:text-white" title="إغلاق">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>



@section('seo_title', 'منصة التطوير | المكتبة الكاملة')
@section('seo_description', 'هذه البيئة مخصصة لأغراض التطوير والاختبار.')


   
    <!-- Staging Interstitial Content -->
    <div class="min-h-screen flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8 bg-gray-50" dir="rtl">
        <div class="space-y-8 max-w-lg mx-auto">
            <!-- Icon 
            <div class="flex justify-center">
                <div class="p-4 bg-amber-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
            </div>
            -->
            <!-- Message -->
            <div class="space-y-4">
                <h1 class="text-3xl font-bold text-gray-900 font-serif">
                  هذه النسخة من الموقع قيد التطوير والاختبار
                </h1>
                <div class="space-y-2 text-gray-600 text-lg leading-relaxed">
                    <p>
                        أهلاً بك. أنت تتصفح حالياً النسخة التجريبية المخصصة للمطورين .
                    </p>
                    <p>
يعمل فريقنا على تطوير الموقع باستمرار لتقديم أفضل تجربة ممكنة.
                </p>
<!--
                    <p class="text-sm text-gray-500">
                        البيانات والمحتوى هنا قد لا تكون محدثة أو نهائية.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-4 pt-4">
                <a href="{{ $production_url ?? 'https://alkamelah.com' }}" 
                   class="w-full inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-[#2C6E4A] hover:bg-[#1a4a3a] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                     الانتقال إلى الموقع الرسمي
                </a>
                
                {{-- Only show this linking if we are dev, but user won't see this unless they know the secret URL anyway --}}
            </div>
             <p class="text-xs text-gray-400 mt-8">
developed by osaid salah 
            </p>
        </div>
    </div>
   
@endsection

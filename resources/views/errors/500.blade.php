@extends('layouts.app')

@section('seo_title', 'خطأ في الخادم | المكتبة الكاملة')
@section('seo_description', 'عذراً، حدث خطأ غير متوقع في الخادم.')

@section('content')
   
    <!-- 500 Content -->
    <div class="min-h-screen flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8 bg-gray-50" dir="rtl">
        <div class="space-y-8 max-w-lg mx-auto">
            <!-- Large 500 Text -->
            <h1 class="text-9xl font-bold text-gray-200 tracking-widest font-serif select-none" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                500
            </h1>
            
            <!-- Message -->
            <div class="space-y-4 relative -top-8">
                <h2 class="text-3xl font-bold text-gray-900 font-serif">
                   خطأ غير متوقع
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    عذراً، حدث خطأ داخلي في الخادم. نحن نعمل على إصلاحه الآن.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="{{ route('home') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-[#2C6E4A] hover:bg-[#1a4a3a] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                     العودة للرئيسية
                </a>

                <a href="{{ route('search.static') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border-2 border-[#2C6E4A] text-base font-medium rounded-full text-[#2C6E4A] bg-transparent hover:bg-[#2C6E4A] hover:text-white transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    انتقل الى صفحة البحث المتقدم
                </a>
            </div>
        </div>
    </div>
   
@endsection

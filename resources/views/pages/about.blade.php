@extends('layouts.app')

@section('title', 'من نحن')

@section('content')
    <!-- Header -->
    @include('components.layout.header')

    <div class="min-h-screen bg-[#fafafa] relative overflow-hidden">
        <!-- Section Background Pattern -->
        <div class="absolute inset-0 pointer-events-none"
            style="background-image: url('{{ asset('assets/Frame 1321314420.png') }}'); background-repeat: repeat; background-size: 800px; background-attachment: fixed;">
        </div>

        <section class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20" dir="rtl">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center">
                        <img src="{{ asset('images/group0.svg') }}" alt="Icon" class="w-full h-full object-contain">
                    </div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-[#1a3a2a]">من نحن</h2>
                </div>
            </div>

            <!-- Content Card -->
            <div
                class="bg-white rounded-[2rem] shadow-xl shadow-green-900/5 overflow-hidden border border-gray-100 p-8 md:p-12">

                <!-- Title -->
                <h3 class="text-2xl md:text-3xl font-bold text-[#2C6E4A] mb-8 text-center">
                    وقف الأقصى الشريف للمعرفة
                </h3>

                <!-- Main Text -->
                <div class="prose prose-lg max-w-none text-gray-700 leading-loose"
                    style="font-size: 1.15rem; line-height: 2.2;">
                    <p class="mb-6 text-justify">
                        يسرنا المساهمة في مشروع الكتاب البحثي في زمن تطور المعرفة وانتشارها وشيوع كتاب PDF بعد كثرة الطباعة
                        الورقية.
                    </p>

                    <p class="mb-6 text-justify">
                        ظهرت الحاجة الكبيرة للكتاب البحثي، وهو لا يغني عن الكتاب الورقي أو الـ PDF، وإنما هي صيغة أخرى في
                        نشر العلم والوصول لكل النتائج.
                    </p>

                    <p class="mb-6 text-justify">
                        لذلك ينبغي للكل من مؤلفين ومحققين ودور نشر تسعى لنشر العلم بالطريقة البحثية أن يساعدوا بإرسال
                        مؤلفاتهم وتحقيقاتهم على موقعنا لتكون من الكتب الوقفية لله لينتفع بها الناس.
                    </p>

                    <p class="text-justify font-semibold text-[#2C6E4A]">
                        وهي لا شك أسرع طريق للاستفادة من الكتاب وشيوعه.
                    </p>
                </div>

                <!-- Decorative Element -->
                <div class="mt-12 flex justify-center">
                    <div class="w-24 h-1 bg-gradient-to-r from-[#2C6E4A] to-[#4A9B6D] rounded-full"></div>
                </div>

                <!-- Contact Section -->
                <div class="mt-12 text-center">
                    <h4 class="text-xl font-bold text-[#1a3a2a] mb-4">تواصل معنا</h4>
                    <p class="text-gray-600 mb-6">للمساهمة في المشروع أو إرسال مؤلفاتكم</p>
                    <a href="mailto:info@example.com"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-[#2C6E4A] text-white rounded-full font-bold hover:bg-[#245a3d] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        راسلنا
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    @include('components.layout.footer')
@endsection
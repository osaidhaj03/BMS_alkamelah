<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $generalSettings->brand_name ?? config('app.name', 'BMS Alkamelah') }}</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('superduper/img/favicon.png') }}" type="image/x-icon">
    
    <!-- Theme CSS via Vite -->
    @vite(['resources/css/app.css'])
    
    <!-- Icon Font -->
    <link rel="stylesheet" href="{{ asset('superduper/fonts/iconfonts/font-awesome/stylesheet.css') }}">
    <!-- Site font -->
    <link rel="stylesheet" href="{{ asset('superduper/fonts/webfonts/public-sans/stylesheet.css') }}" />
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('superduper/css/vendors/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('superduper/css/vendors/jos.css') }}" />
    <link rel="stylesheet" href="{{ asset('superduper/css/style.min.css') }}" />
    
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    
    @stack('css')
    @livewireStyles
    
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <main>
        {{ $slot }}
    </main>

    <x-superduper.footer />

    {{-- الزر العائم ونموذج الملاحظات --}}
    @include('partials.feedback-panel')

    <!-- Vite compiled JS -->
    @vite(['resources/js/app.js'])
    
    <!--Vendor js-->
    <script src="{{ asset('superduper/js/vendors/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('superduper/js/vendors/fslightbox.js') }}"></script>
    <script src="{{ asset('superduper/js/vendors/jos.min.js') }}"></script>
    <script src="{{ asset('superduper/js/main.js') }}"></script>
    
    @livewireScripts
    @stack('js')
</body>
</html>
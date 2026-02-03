<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'المكتبة الكاملة - وقف الأقصى الشريف للمعرفة' }}</title>
    <meta name="description" content="{{ $description ?? 'المكتبة الكاملة | وقف الأقصى الشريف للمعرفة. منصة تراثية رقمية شاملة تضم آلاف الكتب والمخطوطات والمراجع في مختلف العلوم الإسلامية والعربية.' }}">
    <meta name="keywords" content="مكتبة, كتب, تراث, مخطوطات, المكتبة الكاملة, وقف الأقصى الشريف, كتب إسلامية, مراجع">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $title ?? 'المكتبة الكاملة - وقف الأقصى الشريف للمعرفة' }}" />
    <meta property="og:description" content="{{ $description ?? 'المكتبة الكاملة | وقف الأقصى الشريف للمعرفة. منصة تراثية رقمية شاملة تضم آلاف الكتب والمخطوطات والمراجع.' }}" />
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}" />
    <meta property="og:site_name" content="المكتبة الكاملة" />
    <meta property="og:locale" content="ar_AR" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $title ?? 'المكتبة الكاملة - وقف الأقصى الشريف للمعرفة' }}" />
    <meta property="twitter:description" content="{{ $description ?? 'المكتبة الكاملة | وقف الأقصى الشريف للمعرفة. منصة تراثية رقمية شاملة.' }}" />
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "المكتبة الكاملة",
        "alternateName": ["Al-Kamelah Library", "وقف الأقصى الشريف للمعرفة"],
        "url": "https://alkamelah.com",
        "description": "وقف الأقصى الشريف للمعرفة وهي مكتبة تراثية رقمية شاملة.",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "https://alkamelah.com/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    
    <!-- Google Search Console Verification (Placeholder) -->
    <!-- <meta name="google-site-verification" content="YOUR_CODE_HERE" /> -->

    <!-- Tailwind CSS (CDN for prototype speed) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                        serif: ['Amiri', 'serif'],
                    },
                    colors: {
                        green: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .font-serif {
            font-family: 'Amiri', serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">
    {{ $slot }}
</body>

</html>
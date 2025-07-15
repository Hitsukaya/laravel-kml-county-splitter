<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="google-site-verification" content="Lgxe17uOFNhnkA25R3Sb60eiE_ksQNuN3Y_a2NahcrE" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('meta_title', config('app.name'))</title>

        <meta name="description" content="@yield('meta_description', 'KML Analyzer is a powerful yet simple tool that allows you to upload, analyze, and convert KML files to CSV format. Built with innovation and reliability in mind.')">
        <meta name="keywords" content="Hitsukaya, KML Analyzer, KML to CSV, geospatial data, mapping tools, web application, data extraction, GIS, web development, CSV export">
        <meta name="author" content="Valentaizar Hitsukaya">

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon/favicon-16x16.png') }}">

        <meta property="og:type" content="app">
        <meta property="og:title" content="@yield('meta_title', config('app.name'))">
        <meta property="og:description" content="@yield('meta_description', 'KML Analyzer is a powerful yet simple tool that allows you to upload, analyze, and convert KML files to CSV format. Built with innovation and reliability in mind.')">
        <meta property="og:image" content="@yield('meta_image', asset('assets/images/summary_large_image/summary_large_image-hitsukaya.png'))">
        <meta property="og:url" content="{{ url()->current() }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('meta_title', config('app.name'))">
        <meta name="twitter:description" content="@yield('meta_description', 'KML Analyzer is a powerful yet simple tool that allows you to upload, analyze, and convert KML files to CSV format. Built with innovation and reliability in mind.')">
        <meta name="twitter:image" content="@yield('meta_image', asset('assets/images/summary_large_image/summary_large_image-hitsukaya.png'))">

        <link rel="canonical" href="{{ url()->current() }}">

        {{-- <script>
            if (typeof(Storage) !== "undefined") {
                if(localStorage.getItem('dark_mode') && localStorage.getItem('dark_mode') == 'true'){
                    document.documentElement.classList.add('dark');
                }
            }
        </script> --}}

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles


    </head>
    <body class="font-sans antialiased bg-white">

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>

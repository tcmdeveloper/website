{{-- resources/views/components/layouts/app.blade.php --}}

@props([
    'title' => null,
    'subtitle' => null,
    'meta' => [],
    'breadcrumbs' => [],  
])

@php
    $meta = array_merge([
        'title' => 'Official website',
        'description' => 'Official site of True Crime Metrix. This is where we put all the information about the true crime cases we cover on the YouTube channel.',
        'image' => asset('images/og-default.jpg'),
        'canonical' => url()->current(),
        'robots' => 'index,follow',
    ], $meta);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Title --}}
    <title>{{ $meta['title'] . ' | ' . config('app.name') }}</title>
    <meta name="title" content="{{ $meta['title'] . ' | ' . config('app.name') }}">


    {{-- SEO description --}}
    <meta name="description" content="{{ $meta['description'] }}">


    {{-- Favicons --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">


    {{-- Open Graph (social sharing) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $meta['title'] . ' | ' . config('app.name') }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:image" content="{{ $meta['image'] }}">
    <meta property="og:url" content="{{ url()->current() }}">


    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">


    {{-- Prevent indexing on dev (optional) --}}
    @if(app()->environment('local'))
        <meta name="robots" content="noindex,nofollow">
    @endif


    {{-- Vite (production-safe) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Page-specific styles --}}
    @stack('styles')


    @if(app()->environment('production') && !auth()->check())
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            function loadAnalytics() {
                const script = document.createElement('script');

                script.src =
                    'https://www.googletagmanager.com/gtag/js?id=G-41956QHQLE';

                script.async = true;

                script.onload = () => {
                    gtag('js', new Date());

                    gtag('config', 'G-41956QHQLE');
                };

                document.head.appendChild(script);
            }

            if ('requestIdleCallback' in window) {
                requestIdleCallback(loadAnalytics);
            } else {
                setTimeout(loadAnalytics, 1);
            }
        </script>
    @endif


    {{-- Google AdSense --}}
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5443411235770747"
     crossorigin="anonymous"></script>
     
</head>

<body 
    x-data="{ menuOpen: false }"
    :class="{ 'overflow-hidden': menuOpen }"
    class="pt-[61px] flex flex-col min-h-screen bg-gray-50 text-gray-800 font-body"
>

    <x-layouts.navigation />

    <x-ui.content-navigation/>

    @if(! empty($breadcrumbs))
        <x-ui.breadcrumbs :breadcrumbs="$breadcrumbs" />
    @endif

    <x-ui.page-headings
        :title="$title ?? null"
        :subtitle="$subtitle ?? null"
    />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-layouts.footer />

    @stack('scripts')

</body>
</html>
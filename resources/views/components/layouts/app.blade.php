{{-- resources/views/components/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Title --}}
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="title" content="{{ $title ?? config('app.name') }}">


    {{-- SEO description --}}
    <meta name="description" content="{{ $meta_description ?? 'Default description for your application' }}">


    {{-- Favicons --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">


    {{-- Open Graph (social sharing) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $meta_description ?? '' }}">
    <meta property="og:image" content="{{ $og_image ?? asset('images/default-og.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">


    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">


    <!-- Google Analytics (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-268245269"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-268245269');
    </script>


    {{-- Google AdSense --}}
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5443411235770747"
     crossorigin="anonymous"></script>


    {{-- Prevent indexing on dev (optional) --}}
    @if(app()->environment('local'))
        <meta name="robots" content="noindex,nofollow">
    @endif


    {{-- Vite (production-safe) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Page-specific styles --}}
    @stack('styles')


    {{-- Page-specific scripts (if you still use them) --}}
    @stack('scripts')
</head>

<body 
    x-data="{ menuOpen: false }"
    :class="{ 'overflow-hidden': menuOpen }"
    class="pt-[61px] flex flex-col min-h-screen bg-gray-50 text-gray-800 font-body"
>

    <x-layouts.navigation />

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
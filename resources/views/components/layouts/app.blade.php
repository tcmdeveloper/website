{{-- resources/views/components/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Stack per-page styles --}}
    @stack('styles')

</head>

<body 
    x-data="{ menuOpen: false }"
    :class="{ 'overflow-hidden': menuOpen }"
    class="pt-[61px] flex flex-col min-h-screen bg-gray-50 text-gray-800 font-body"
>

    {{-- NAVBAR --}}
    <x-layouts.navigation />

    {{-- Page headings --}}
    <x-ui.page-headings
        :title="$title ?? null"
        :subtitle="$subtitle ?? null"
    />

    {{-- MAIN CONTENT --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <x-layouts.footer />

    @stack('scripts')
</body>
</html>
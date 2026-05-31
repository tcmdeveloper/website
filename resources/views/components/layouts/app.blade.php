{{-- resources/views/components/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">


    {{-- NAVBAR --}}
    <x-layouts.navigation />

    {{-- Page headings --}}
    <x-ui.page-headings
        :title="$title ?? null"
        :subtitle="$subtitle ?? null"
    />

    {{-- MAIN CONTENT --}}
    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-0">
            {{ $slot }}
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t bg-white">
        <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-500 flex justify-between gap-x-6">
            <span class="grow">© {{ date('Y') }} {{ config('app.name') }}</span>
            <a href="{{ route('pages.terms') }}">Terms</a>
            <a href="{{ route('pages.privacy') }}">Privacy</a>
        </div>
    </footer>

</body>
</html>
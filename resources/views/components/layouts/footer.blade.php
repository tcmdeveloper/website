{{-- resources/views/components/layouts/footer.blade.php --}}

<footer class="border-t border-t-zinc-300 bg-white text-sm text-gray-500">
    <div class="max-w-5xl mx-auto px-4 pt-6 pb-3 md:pb-6  flex justify-center gap-x-6">
        <span class="grow hidden md:inline-block">© {{ date('Y') }} {{ config('app.name') }}</span>
        <a href="/about" class="hover:text-yellow-500">About</a>
        <a href="/contact" class="hover:text-yellow-500">Contact</a>
        <a href="{{ route('pages.terms') }}">Terms</a>
        <a href="{{ route('pages.privacy') }}">Privacy</a>
    </div>

    <span class="w-full text-center pb-6 inline-block md:hidden">© {{ date('Y') }} {{ config('app.name') }}</span>
</footer>
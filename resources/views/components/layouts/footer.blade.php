{{-- resources/views/components/layouts/footer.blade.php --}}

<footer class="border-t border-t-zinc-300 bg-white">
    <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-500 flex justify-between gap-x-6">
        <span class="grow">© {{ date('Y') }} {{ config('app.name') }}</span>
        <a href="/about" class="hover:text-yellow-500">About</a>
        <a href="/contact" class="hover:text-yellow-500">Contact</a>
        <a href="{{ route('pages.terms') }}">Terms</a>
        <a href="{{ route('pages.privacy') }}">Privacy</a>
    </div>
</footer>
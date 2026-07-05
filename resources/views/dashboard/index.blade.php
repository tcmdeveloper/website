<x-layouts.dashboard>

    <x-ui.card class="prose-content">

        <h1 class="mt-0">
            Dashboard
        </h1>


         {{-- Stats --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5">

        {{-- Criminal Cases --}}
        <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-500">
                        Criminal Cases
                    </p>

                    <p class="mt-2 text-4xl font-bold text-stone-900">
                        {{ number_format($criminalCaseCount) }}
                    </p>
                </div>

                <div class="rounded-full bg-stone-100 p-3">
                    <x-heroicon-o-finger-print class="h-7 w-7 text-stone-700" />
                </div>
            </div>
        </div>

        {{-- Categories --}}
        <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-500">
                        Categories
                    </p>

                    <p class="mt-2 text-4xl font-bold text-stone-900">
                        {{ number_format($categoryCount) }}
                    </p>
                </div>

                <div class="rounded-full bg-stone-100 p-3">
                    <x-heroicon-o-folder class="h-7 w-7 text-stone-700" />
                </div>
            </div>
        </div>

        {{-- Articles --}}
        <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-500">
                        Articles
                    </p>

                    <p class="mt-2 text-4xl font-bold text-stone-900">
                        {{ number_format($articleCount) }}
                    </p>
                </div>

                <div class="rounded-full bg-stone-100 p-3">
                    <x-heroicon-o-document class="h-7 w-7 text-stone-700" />
                </div>
            </div>
        </div>

        

        {{-- Documents --}}
        <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-500">
                        Documents
                    </p>

                    <p class="mt-2 text-4xl font-bold text-stone-900">
                        {{ number_format($documentCount) }}
                    </p>
                </div>

                <div class="rounded-full bg-stone-100 p-3">
                    <x-heroicon-o-document-text class="h-7 w-7 text-stone-700" />
                </div>
            </div>
        </div>

        {{-- Videos --}}
        <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-500">
                        Videos
                    </p>

                    <p class="mt-2 text-4xl font-bold text-stone-900">
                        {{ number_format($videoCount) }}
                    </p>
                </div>

                <div class="rounded-full bg-stone-100 p-3">
                    <x-heroicon-o-play class="h-7 w-7 text-stone-700" />
                </div>
            </div>
        </div>

    </div>

    {{-- Main Dashboard Content --}}
    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Recent Articles --}}
        <div class="xl:col-span-2 rounded-lg border border-zinc-200 bg-white shadow-sm">

            <div class="border-b border-zinc-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-stone-900">
                    Recent Articles
                </h2>
            </div>

            <div class="p-6 text-sm text-stone-500">
                Recent articles table goes here...
            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="rounded-lg border border-zinc-200 bg-white shadow-sm">

            <div class="border-b border-zinc-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-stone-900">
                    Quick Actions
                </h2>
            </div>

            <div class="space-y-3 p-6">

                <a
                    href="{{ route('admin.articles.create') }}"
                    class="block rounded-md bg-stone-900 px-4 py-3 text-center text-sm font-medium text-white transition hover:bg-stone-800"
                >
                    New Article
                </a>

                <a
                    href="{{ route('admin.categories.create') }}"
                    class="block rounded-md border border-zinc-300 px-4 py-3 text-center text-sm font-medium text-stone-700 transition hover:bg-stone-100"
                >
                    New Category
                </a>

                <a
                    href="{{ route('admin.articles.index') }}"
                    class="block rounded-md border border-zinc-300 px-4 py-3 text-center text-sm font-medium text-stone-700 transition hover:bg-stone-100"
                >
                    Manage Articles
                </a>

            </div>

        </div>

    </div>

    </x-ui.card>

</x-layouts.dashboard>
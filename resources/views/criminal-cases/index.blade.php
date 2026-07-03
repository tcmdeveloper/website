{{-- resources/views/criminal-cases/index.blade.php --}}

<x-layouts.app
    title="Categories"
    subtitle="Explore posts organized by topic"
>

    <x-ui.container class="max-w-5xl">


        {{-- Criminal Case Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            @forelse($criminalCases as $case)

                <a
                    href="{{ route('criminal-cases.show', $case->slug) }}"
                    class="group block rounded-xl border border-zinc-200 p-6 hover:shadow-md hover:border-green-400 transition bg-white"
                >

                    {{-- Case name --}}
                    <h2 class="text-lg font-semibold group-hover:text-green-600 transition">
                        {{ $case->name }}
                    </h2>

                    {{-- Description --}}
                    @if($case->description)
                        <p class="text-sm text-zinc-500 mt-2">
                            {{ Str::limit($case->description, 300) }}
                        </p>
                    @endif

                    {{-- Meta --}}
                    <div class="mt-4 flex items-center justify-between text-sm text-zinc-400">

                        <span>
                            0 posts
                        </span>

                        <span class="group-hover:text-green-500 transition">
                            View →
                        </span>

                    </div>

                </a>

            @empty

                <div class="col-span-full text-center py-16 text-zinc-500">
                    No criminal cases found.
                </div>

            @endforelse

        </div>

    </x-ui.container>

</x-layouts.app>
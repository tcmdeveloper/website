{{-- resources/views/criminal-cases/index.blade.php --}}

<x-layouts.app
    title="Criminal Cases"
    subtitle="Browse the criminal cases we cover."
>

    <x-ui.container class="max-w-5xl">


        {{-- Criminal Case Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6">

            @forelse($criminalCases as $case)

                <a
                    href="{{ route('cases.show', $case->slug) }}"
                    class="group block rounded-xl border border-zinc-200 p-6 hover:shadow-md hover:border-green-400 transition bg-white"
                >   

                    {{-- Featured image --}}

                    <x-ui.image
                        :image="$case->featuredImage"
                        class="w-full rounded-sm h-48 object-cover mb-4"
                    />
    

                    {{-- Case name --}}
                    <h2 class="text-lg font-semibold group-hover:text-green-600 transition">
                        The {{ $case->name }} Case
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
                            {{ $case->documents_count }} documents
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


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $criminalCases->links() }}
        </div>


    </x-ui.container>


</x-layouts.app>
{{-- resources/views/criminal-cases/index.blade.php --}}

<x-layouts.app
    title="Criminal Cases"
    subtitle="Browse the criminal cases we cover."
>

    <x-ui.container class="max-w-5xl">


        {{-- Criminal Case Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 gap-6">

            @forelse($criminalCases as $case)

                <a
                    href="{{ route('cases.show', $case->slug) }}"
                    class="group block rounded-sm border border-zinc-200 p-6 hover:shadow-md hover:border-red-metrix transition bg-white"
                >   

                    {{-- Featured image --}}
                    
                    <div class="grid grid-cols-12 gap-6">
                        
                        <div class="col-span-3">

                            <x-ui.image
                                :image="$case->display_image"
                                class="w-full sm:w-[219px] object-cover rounded-tl-sm rounded-tr-sm rounded-bl-none rounded-br-none"
                                sizes="(min-width: 640px) 219px, 100vw"
                            />
                        </div>
                        
                        <div class="col-span-9">

                            {{-- Case name --}}
                            <h2 class="text-lg font-semibold group-hover:text-red-metrix transition">
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

                                {{-- <span>
                                    {{ $case->documents_count }} documents
                                </span> --}}

                                <span class="group-hover:text-red-metrix transition">
                                    View →
                                </span>

                            </div>

                        </div>
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
{{-- resources/views/timelines/index.blade.php --}}

<x-layouts.app
    title="Timeline"
    subtitle="Browse the criminal cases timelines."
>

    <x-ui.container class="max-w-5xl">


        {{-- Criminal Case Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 gap-6">

            @forelse($timelines as $timeline)

                <a
                    href="{{ route('cases.timelines', [$timeline->criminalCase->slug, $timeline->slug]) }}"
                    class="group block rounded-sm border border-zinc-200 p-6 hover:shadow-md hover:border-red-metrix transition bg-white"
                >   

                    {{-- Featured image --}}
                    
                    <div class="flex gap-6 flex-col md:flex-row">
                        {{-- <x-ui.image
                            :image="$timeline->featuredImage"
                            class="rounded-sm h-56 md:h-28 object-cover mb-4"
                        /> --}}
                        
                        <div>

                            {{-- Case name --}}
                            <h2 class="text-lg font-semibold group-hover:text-red-metrix transition">
                                The {{ $timeline->criminalCase->name }} Case: {{ $timeline->name }} 
                            </h2>

                            {{-- Description --}}
                            @if($timeline->description)
                                <p class="text-sm text-zinc-500 mt-2">
                                    {{ Str::limit($timeline->description, 300) }}
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
                    No timelines found.
                </div>

            @endforelse

        </div>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $timelines->links() }}
        </div>


    </x-ui.container>


</x-layouts.app>
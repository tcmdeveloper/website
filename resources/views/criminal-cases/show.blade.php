{{-- resources/views/criminal-cases/show.blade.php --}}

<x-layouts.app
    title="The {{$criminalCase->name}} Case"
    :subtitle="$criminalCase->description"
    :meta="[
        'title' => $criminalCase->name . ' Case',
        'description' => Str::limit(
            strip_tags($criminalCase->description),
            160),
    ]"
    {{-- :breadcrumbs="[
        'href' => route('cases.index'),
        'label' => 'Back to Criminal Cases'
    ]" --}}
>

    <x-ui.container class="max-w-5xl">

        {{--
            <div class="mx-auto max-w-5xl">
                <div class="w-full text-center mb-10">
                    <x-ui.button
                        size="xs"
                        href=""
                    >   
                        <x-heroicon-o-arrow-left class="w-3 h-3" />
                        Back to Criminal Cases
                    </x-ui.button>
                </div>
            </div> 
        --}}

        {{-- CASE DOCUMENTS --}}

        <section aria-labelledby="case-documents-heading" class="mb-20">

            {{-- Screen reader heading --}}

            <h2 id="case-documents-heading" class="sr-only">
                Court documents for the {{ $criminalCase->name }} case
            </h2>


            {{-- Document list --}}

            @if($criminalCase->documents->isEmpty())

                <div class="mt-6 rounded-lg border border-gray-200 bg-white p-8 text-center">
                    <p class="text-gray-600">
                        No documents have been added for this case.
                    </p>
                </div>

            @else

                <div class="mt-6 divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white">

                    @foreach($criminalCase->documents as $document)

                        <a
                            href="{{ route('documents.show', [$document->criminalCase, $document]) }}"
                            class="block p-6 transition hover:bg-gray-50"
                        >
                            <div class="flex gap-5">

                                {{-- Thumbnail --}}
                                @if($document->coverPage)
                                    <x-ui.image
                                        :image="$document->coverPage"
                                        class="w-full sm:w-[80px] object-cover rounded-xs shadow-sm rounded-bl-none rounded-br-none border border-zinc-200"
                                        sizes="(min-width: 640px) 80px, 100vw"
                                    />
                                @endif

                                {{-- Text --}}
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $document->name }}
                                    </h3>

                                    @if($document->description)
                                        <p class="mt-2 line-clamp-2 text-sm text-gray-600">
                                            {{ $document->description }}
                                        </p>
                                    @endif

                                    <div class="mt-3 flex flex-wrap gap-x-2 gap-y-2 text-xs text-gray-500">
                                        <span>{{ $document->pages }} pages</span>
                                        <span>&middot;</span>
                                        <span>{{ $document->published_at->format('j M Y') }}</span>
                                        <span>&middot;</span>
                                        <span>{{ number_format($document->views) }} views</span>
                                    </div>
                                </div>

                                {{-- Button --}}
                                <div class="shrink-0 text-sm font-medium text-indigo-600">
                                    Open →
                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

                
                @if(method_exists($criminalCase->documents, 'links'))
                    <div class="mt-8">
                        {{ $criminalCase->documents->links() }}
                    </div>
                @endif

            @endif


        </section>




        {{-- ARTICLES --}}

        <section aria-labelledby="case-articles-heading">

            <div 
                class="my-10 px-3 relative text-center max-w-5xl mx-auto"
            >
                <h2 id="case-articles-heading" class="text-xl sm:text-3xl font-bold tracking-tight text-zinc-700">
                    True crime articles about the {{ $criminalCase->name }} case
                </h2>
            </div>

            <x-articles.grid :articles="$criminalCase->articles" />

        </section>
        
    </x-ui.container>
    

</x-layouts.app>
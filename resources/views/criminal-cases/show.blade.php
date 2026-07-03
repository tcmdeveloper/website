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
>

    <x-ui.container class="max-w-5xl">

        <section aria-labelledby="documents-heading">

            <h2 id="documents-heading" class="sr-only">
                Documents
            </h2>

            @if($criminalCase->documents->isEmpty())
                <div class="mt-6 rounded-lg border border-gray-200 bg-white p-8 text-center">
                    <p class="text-gray-600">
                        No documents have been added for this criminal case yet.
                    </p>
                </div>
            @else
                <div class="mt-6 divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white">
                    @foreach($criminalCase->documents as $document)
                        <a
                            href="{{ route('documents.show', [$document->criminalCase, $document]) }}"
                            class="block p-6 transition hover:bg-gray-50"
                        >
                            <div class="flex items-start justify-between gap-6">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $document->name }}
                                    </h3>

                                    @if($document->description)
                                        <p class="mt-2 line-clamp-2 text-sm text-gray-600">
                                            {{ $document->description }}
                                        </p>
                                    @endif

                                    <div class="mt-3 flex flex-wrap gap-x-6 gap-y-2 text-sm text-gray-500">
                                        {{ $document->pages }} pages
                                        @if($document->published_at)
                                            &middot;
                                            Published {{ $document->published_at->format('j M Y') }}
                                        @endif
                                    </div>
                                </div>

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
        
    </x-ui.container>
    

</x-layouts.app>
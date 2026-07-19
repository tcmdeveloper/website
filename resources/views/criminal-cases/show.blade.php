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

        {{-- DOCKET ENTRIES --}}

        <h2 class="text-3xl md:text-4xl text-center md:text-left font-bold px-3 pb-6 text-zinc-900">
            Court Docket / {{ $criminalCase->name }}
        </h2>

        <div class="grid grid-cols-12 gap-4">
            
            <div class="col-span-12 md:col-span-8 rounded-sm border border-gray-200 bg-white">

                <div class="h-[600px] overflow-y-auto">

                    <table class="w-full text-xs">

                        <thead class="sticky top-0 bg-zinc-50 z-10">
                            <tr class="border-b border-zinc-200 text-left text-zinc-600 font-medium">
                                <th class="px-6 py-4">Seq.</th>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4 text-center">Attachment</th>
                                <th class="px-6 py-4">Date Filed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($criminalCase->docketEntries->sortByDesc('filed_at') as $docketEntry)
                                <tr class="border-b border-zinc-100">
                                    <td class="px-6 py-4">{{ $docketEntry->sequence_number }}</td>
                                    <td class="px-6 py-4">{{ $docketEntry->title }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center">
                                            @if($docketEntry->document)
                                                <a href="{{ route('documents.show', [$criminalCase, $docketEntry->document]) }}">
                                                    <x-heroicon-s-document class="w-4" />
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $docketEntry->filed_at->format('M d Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

            <aside class="col-span-12 md:col-span-4">

                <h2 class="text-xl font-bold px-3 py-3 text-white rounded-sm border border-gray-200 bg-red-metrix mb-2">
                    Case Articles
                </h2>

                <x-articles.grid    
                    :articles="$criminalCase->articles" 
                    :sideList="true"
                 />
            </aside>

        </div>


        {{-- CASE DOCUMENTS --}}

        <section aria-labelledby="case-documents-heading" class="mb-20">

            {{-- Screen reader heading --}}

            <h2 class="text-4xl font-bold px-3 pt-12 text-zinc-900">
                Case Filings
            </h2>


            {{-- Document list --}}
            @if($documents->isEmpty())

                <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
                    <p class="text-gray-600">
                        No documents have been added for this case.
                    </p>
                </div>

            @else

                <div class="grid grid-cols-12 gap-6 mt-6">

                    <div class="col-span-12 md:col-span-8 mt-6 divide-y divide-gray-200 rounded-sm border border-gray-200 bg-white">

                        @foreach($documents as $document)

                            <a
                                href="{{ route('documents.show', [$document->criminalCase, $document]) }}"
                                class="block p-6 transition hover:bg-gray-50"
                            >
                                <div class="flex gap-5">

                                    {{-- Thumbnail --}}
                                    @if($document->coverPage)
                                        <x-ui.image
                                            :image="$document->coverPage"
                                            class="w-[100px] object-cover rounded-xs shadow-sm rounded-bl-none rounded-br-none border border-zinc-200"
                                            sizes="(min-width: 640px) 80px, 100vw"
                                        />
                                    @endif

                                    {{-- Text --}}
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-md font-semibold text-gray-900">
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
                                            <span>{{ $document->docketEntry?->filed_at->format('j M Y') }}</span>
                                            <span>&middot;</span>
                                            <span>{{ number_format($document->views) }} views</span>
                                        </div>
                                        <div class="block md:hidden shrink-0 text-sm font-medium text-indigo-600 mt-3">
                                            Open →
                                        </div>
                                    </div>

                                    {{-- Button --}}
                                    <div class="hidden md:block shrink-0 text-sm font-medium text-indigo-600">
                                        Open →
                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>

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
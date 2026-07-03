{{-- resources/views/documents/show.blade.php --}}

<x-layouts.app
    :meta="[
        'title' => filled($document->meta_title)
            ? $document->meta_title
            : $document->name,

        'description' => Str::limit(
            strip_tags(
                filled($document->meta_description)
                    ? $document->meta_description
                    : $document->description
            ),
            200
        ),
    ]"
>

    <x-ui.card class="max-w-3xl mx-auto flex flex-col items-center shadow-none! bg-transparent! border-none! ">
        
        <div class="flex flex-col items-center prose-content">

            {{-- PUBLISHED DATE --}}
            <div class="mt-5 text-xs font-light uppercase tracking-widest">
                {{ $document->pages }} pages
                @if($document->published_at)
                    &middot;
                    {{ $document->published_at->format('j M Y') }}
                @endif
            </div>


            {{-- NAME AND DESCRIPTION --}}
            <h1 class="text-center mt-2 mb-0">
                {{ $document->name }}
            </h1>

            <p class="text-center mt-3 mb-3! font-heading text-xl font-light">
                {{ $document->description }}
            </p>


            {{-- CATEGORY/AUTHOR PIPS --}}
            <span>
                <x-ui.category-pip
                    href="{{route('cases.show', $document->criminalCase->slug)}}"
                >
                    {{$document->criminalCase->name}} case
                </x-ui.category-pip>

                <a class="inline-flex items-center rounded-full px-3 py-1 text-xs font-normal transition bg-red-500 text-white hover:bg-lime-20 no-underline">
                    Uploaded by Metrix
                </a>
            </span>

        </div>


        <div class="mt-10 space-y-10">

            @foreach ($document->documentPages as $page)

                <figure class="rounded-sm border border-gray-200 bg-white p-0 shadow-sm">
                    <img
                        src="{{ Storage::url($page->image_path) }}"
                        alt="Page {{ $page->page_number }}"
                        loading="lazy"
                        class="block mx-auto h-auto w-full max-w-3xl rounded-sm"
                    >

                    {{-- <figcaption class="mt-4 text-center text-sm text-gray-500">
                        Page {{ $page->page_number }}
                    </figcaption> --}}

                </figure>

            @endforeach

        </div>

    </x-ui.card>

</x-layouts.app>
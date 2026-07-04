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
                    Uploaded by {{$document->author->display_name}}
                </a>
            </span>

        </div>


        <div
    x-data="{
        open: false,
        current: 0,
        images: [
            @foreach($document->documentPages as $page)
                '{{ Storage::url($page->image_path) }}',
            @endforeach
        ],

        show(index) {
            this.current = index;
            this.open = true;
            document.body.classList.add('overflow-hidden');
        },

        close() {
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },

        next() {
            if (this.current < this.images.length - 1) {
                this.current++;
            }
        },

        prev() {
            if (this.current > 0) {
                this.current--;
            }
        }
    }"

    @keydown.escape.window="close()"
    @keydown.right.window="if(open) next()"
    @keydown.left.window="if(open) prev()"
>

    <div class="mt-10 space-y-10">

        @foreach ($document->documentPages as $page)

            <figure class="rounded-sm border border-gray-200 bg-white shadow-sm">

                <img
                    src="{{ Storage::url($page->image_path) }}"
                    alt="Page {{ $page->page_number }}"
                    loading="lazy"
                    class="block mx-auto w-full max-w-3xl cursor-zoom-in rounded-sm transition hover:opacity-95"
                    @click="show({{ $loop->index }})"
                >

            </figure>

        @endforeach

    </div>

    {{-- Lightbox --}}
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center"
        @click="close()"
    >

        {{-- Previous --}}
        <button
            x-show="current > 0"
            @click.stop="prev()"
            class="absolute left-6 text-white text-6xl hover:text-gray-300 select-none"
        >
            ‹
        </button>

        {{-- Image --}}
        <img
            :src="images[current]"
            class="max-w-[95vw] max-h-[95vh] object-contain"
            @click.stop
        >

        {{-- Next --}}
        <button
            x-show="current < images.length - 1"
            @click.stop="next()"
            class="absolute right-6 text-white text-6xl hover:text-gray-300 select-none"
        >
            ›
        </button>

        {{-- Close --}}
        <button
            @click="close()"
            class="absolute top-6 right-6 text-white text-5xl hover:text-gray-300"
        >
            ✕
        </button>

        {{-- Page Counter --}}
        <div
            class="absolute bottom-6 left-1/2 -translate-x-1/2 rounded bg-black/60 px-4 py-2 text-white"
        >
            <span x-text="current + 1"></span>
            /
            <span x-text="images.length"></span>
        </div>

    </div>

</div>  

    </x-ui.card>

</x-layouts.app>
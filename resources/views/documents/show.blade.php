{{-- resources/views/documents/show.blade.php --}}

<x-layouts.app
    title="Court Docket: {{ $document->criminalCase->name }}"
    :subtitle="$document->name"
    :meta="[
        'title' => $document->name,
        'description' => 'Read the court filing from the ' . $document->criminalCase->name . ' case. Published on ' . $document->docketEntry?->filed_at->format('M d Y'),
    ]"
    
>

    <div
        x-data="{
            open: false,
            current: 0,

            images: [
                @foreach ($document->documentPages as $page)
                    '{{ $page->url(640, 'avif') }}',
                @endforeach
            ],

            touchStartX: 0,
            touchEndX: 0,

            show(index) {
                this.current = index;
                this.open = true;

                document.body.classList.add(
                    'overflow-hidden'
                );
            },

            close() {
                this.open = false;

                document.body.classList.remove(
                    'overflow-hidden'
                );
            },

            next() {
                this.current =
                    (this.current + 1)
                    % this.images.length;
            },

            prev() {
                this.current =
                    (this.current - 1 + this.images.length)
                    % this.images.length;
            },

            touchStart(e) {
                this.touchStartX =
                    e.changedTouches[0].screenX;
            },

            touchEnd(e) {
                this.touchEndX =
                    e.changedTouches[0].screenX;

                const distance =
                    this.touchEndX
                    - this.touchStartX;

                if (
                    Math.abs(distance) < 50
                ) {
                    return;
                }

                distance < 0
                    ? this.next()
                    : this.prev();
            },
        }"

        @keydown.escape.window="close()"
        @keydown.right.window="if (open) next()"
        @keydown.left.window="if (open) prev()"

        class="mx-auto max-w-4xl px-4 flex justify-center"
    >

        

        

        {{-- MAIN COLUMN --}}
        <main class="min-w-0">






                <div class="mb-4 flex justify-center gap-2 mb-10">

                    @if ($document->criminalCase)

                        <x-ui.category-pip
                            href="{{ route('cases.show', $document->criminalCase->slug) }}"
                        >
                            {{ $document->criminalCase->name }} Case
                        </x-ui.category-pip>

                    @endif

                    <span class="rounded-full bg-lime-100 px-3 py-1 text-xs">
                        {{ $document->pages }} pages
                    </span>

                    @if ($document->docketEntry?->filed_at)

                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs">
                            {{ $document->docketEntry?->filed_at->format('M d Y') }}
                        </span>

                    @endif

                </div>

               

         



                @foreach ($document->documentPages as $index => $page)
                {{-- @dd($page->url) --}}
                    <button
                        type="button"
                        class="block w-full text-left mb-10"
                        @click="show({{ $index }})"
                    >

                        <x-ui.image
                            :image="$page"
                            alt="Page {{ $page->page_number }}"
                            class="w-full rounded border border-zinc-200 bg-white shadow"
                            sizes="(min-width: 640px) 640px, 100vw"
                        />

                    </button>

                @endforeach



        </main>

        @include('documents.partials.lightbox')

    </div>

</x-layouts.app>
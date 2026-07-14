{{-- resources/views/documents/show.blade.php --}}

<x-layouts.app
    :title="$document->criminalCase->name . ': Documents'"
    
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

        class="mx-auto max-w-7xl px-4 py-8 lg:grid lg:grid-cols-12 lg:gap-10"
    >

        

        {{-- Sidebar --}}
        <aside class="mt-10 lg:mt-0 col-span-4">

            <div class="lg:sticky lg:top-24">

                <x-ui.card>

                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide">
                        Document details
                    </h2>

                    <dl class="space-y-3 text-sm">

                        <div class="flex justify-between gap-4">

                            <dt>Pages</dt>

                            <dd>
                                {{ $document->pages }}
                            </dd>

                        </div>

                        <div class="flex justify-between gap-4">

                            <dt>Uploaded by</dt>

                            <dd>
                                {{ $document->author->display_name }}
                            </dd>

                        </div>

                        @if ($document->published_at)

                            <div class="flex justify-between gap-4">

                                <dt>Published</dt>

                                <dd>
                                    {{ $document->published_at->format('j M Y') }}
                                </dd>

                            </div>

                        @endif

                    </dl>


                </x-ui.card>

            </div>

        </aside>

        {{-- MAIN COLUMN --}}
        <main class="min-w-0 col-span-8">

            {{-- Header --}}
            <header class="mb-10 prose-content">

                <h1 class="mt-0">
                    {{ $document->name }}
                </h1>

                @if(! empty($document->description))
                    <p>
                        {{ $document->description }}
                    </p>
                @endif

                <div class="mb-4 flex flex-wrap gap-2">

                    @if ($document->criminalCase)

                        <x-ui.category-pip
                            href="{{ route('cases.show', $document->criminalCase->slug) }}"
                        >
                            {{ $document->criminalCase->name }} case
                        </x-ui.category-pip>

                    @endif

                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs">
                        {{ $document->pages }} pages
                    </span>

                    @if ($document->published_at)

                        <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs">
                            {{ $document->published_at->format('j M Y') }}
                        </span>

                    @endif

                </div>

               

            </header>

            {{-- Pages --}}
            <section class="space-y-8">

                @foreach ($document->documentPages as $index => $page)
                {{-- @dd($page->url) --}}
                    <button
                        type="button"
                        class="block w-full text-left"
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

            </section>

        </main>

        @include('documents.partials.lightbox')

    </div>

</x-layouts.app>
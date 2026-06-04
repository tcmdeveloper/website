{{-- resources/views/articles/show.blade.php --}}

<x-layouts.app
    :title="$article->title"
    :subtitle="$article->excerpt ?? null"
>

    <div class="max-w-3xl mx-auto">

        {{-- ARTICLE HEADER --}}
        <header class="mb-10">

            <div class="text-sm text-indigo-600 font-semibold uppercase tracking-wide">
                {{ $article->category->name ?? 'News' }}
            </div>

            <h1 class="text-4xl font-bold leading-tight mt-2 text-gray-900">
                {{ $article->title }}
            </h1>

            {{-- META --}}
            <div class="mt-4 flex items-center gap-3 text-sm text-gray-500">

                <img
                    src="{{ $article->author->avatar ?? 'https://via.placeholder.com/40' }}"
                    class="w-8 h-8 rounded-full"
                    alt="Author"
                >

                <span class="font-medium text-gray-700">
                    {{ $article->author->name ?? 'Author' }}
                </span>

                <span>•</span>

                <span>
                    {{ optional($article->published_at)->format('M d, Y') }}
                </span>

                <span>•</span>

                <span>
                    {{ $article->reading_time ?? '5 min read' }}
                </span>

            </div>

            {{-- FEATURED IMAGE --}}
            @if(!empty($article->featured_image))
                <img
                    src="{{ $article->featured_image }}"
                    class="mt-6 w-full rounded-xl object-cover max-h-[420px]"
                    alt="{{ $article->title }}"
                >
            @endif

        </header>

        {{-- ARTICLE CONTENT --}}
        <article class="prose prose-lg max-w-none prose-indigo">
            {!! Str::markdown($article->content) !!}
        </article>

        {{-- TAGS --}}
        @if(!empty($article->tags))
            <div class="mt-10 flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                    <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- INLINE CTA (less aggressive, more editorial) --}}
        <div class="mt-14 border rounded-2xl p-6 bg-gray-50">
            <h3 class="text-xl font-semibold text-gray-900">
                Build your own SaaS faster
            </h3>

            <p class="mt-2 text-gray-600 text-sm">
                Join thousands of developers shipping products faster with our platform.
            </p>

            <a href="/register"
               class="inline-block mt-4 bg-indigo-600 text-white text-sm font-semibold px-5 py-2 rounded-lg hover:bg-indigo-700">
                Get Started
            </a>
        </div>

        {{-- AUTHOR BIO --}}
        <footer class="mt-14 flex items-center gap-4 border-t pt-6">

            <img
                src="{{ $article->author->avatar ?? 'https://via.placeholder.com/60' }}"
                class="w-12 h-12 rounded-full"
                alt="Author"
            >

            <div>
                <div class="font-semibold text-gray-900">
                    {{ $article->author->display_name ?? 'Author Name' }}
                </div>

                <div class="text-sm text-gray-500">
                    {{ $article->author->bio ?? 'Writer & builder' }}
                </div>
            </div>

        </footer>

    </div>

</x-layouts.app>
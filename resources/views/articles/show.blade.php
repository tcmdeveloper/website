{{-- resources/views/articles/show.blade.php --}}

<x-layouts.app>
       

    <x-ui.card class="max-w-3xl mx-auto flex flex-col items-center markdown bg-transparent! border-none shadow-none!">

        <div class="my-1 mt-4 text-sm">{{ $article->created_at->format('M d, Y') }}</div>

        <h1 class="text-center my-4 mb-1!">
            {{ $article->title }}
        </h1>

        <h2 class="text-center mb-3!">
            {{ $article->excerpt }}
        </h2>

        
        {{-- FEATURED IMAGE --}}
        @if(!empty($article->featured_image))
            <img
                src="{{ url($article->featured_image) }}"
                class="my-6 w-full rounded-xs object-contain max-w-3xl"
                alt="{{ $article->title }}"
            >
        @endif

        <div class="flex gap-4 border border-zinc-300 w-full px-3 py-2 text-sm mb-6 bg-amber-50">
            <span>Author: Metrix</span>
            @if(!empty($article->category))
                <span>|</span>
                <span>Category: <a href="{{url('/categories')}}" class="link text-blue-800!">{{ $article->category->name }}</a></span>
            @endif
        </div>

        {{-- ARTICLE CONTENT --}}
        <article class="prose max-w-none markdown">
            {!! $contentHtml !!}
        </article>


    </x-ui.card>
    

</x-layouts.app>

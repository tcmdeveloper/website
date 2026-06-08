{{-- resources/views/articles/show.blade.php --}}

<x-layouts.app>
       

    <x-ui.card class="max-w-6xl mx-auto flex flex-col items-center markdown shadow-none! bg-transparent! border-none!">

        <div class="my-1 mt-5 text-lg font-bold flex flex-row justify-center w-full">
            <span>{{ $article->created_at->format('M d, Y') }}</span>
            {{-- <span>
                    <x-ui.category-pip
                        href="{{route('categories.show', $article->category->slug)}}"
                        color="{{$article->category->color}}"
                    >
                        {{$article->category->name}}
                    </x-ui.category-pip>
                </span> --}}
        </div>

        <h1 class="text-center my-8 mb-8!">
            {{ $article->title }}
        </h1>

        <span>
                    <x-ui.category-pip
                        href="{{route('categories.show', $article->category->slug)}}"
                        color="{{$article->category->color}}"
                    >
                        {{$article->category->name}}
                    </x-ui.category-pip>
                    <a class="inline-flex items-center rounded-full px-3 py-1 text-sm font-normal transition bg-red-500 text-white hover:bg-lime-20">by {{$article->author->display_name}}</a>
                </span>

        <h2 class="text-center mt-7 mb-5!">
            {{ $article->excerpt }}
        </h2>

        
        {{-- FEATURED IMAGE --}}
        <img
            src="{{ asset($article->featured_image->path) }}"
            class="my-6 w-full rounded-xs object-contain shadow-sm"
            alt="{{ $article->featured_image->alt_text }}"
        >
        

        <div class="flex flex-col gap-4 border border-zinc-300 w-full px-3 py-2 text-xs mb-6 bg-amber-50">
            {{ $article->excerpt }}
            <span>
                Image source: 
                <a href="/" target="_blank" class="link ml-1">
                    {{ $article->featured_image->source }}
                    <x-ui.icon name="arrow-top-right-on-square" size="xs" class="ml-1 relative -top-1 w-2!" />
                </a>
            </span>
        </div>

        {{-- ARTICLE CONTENT --}}
        <article class="prose max-w-none markdown">
            {!! $article->contentHtml !!}
        </article>


    </x-ui.card>
    

</x-layouts.app>

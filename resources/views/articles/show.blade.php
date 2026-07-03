{{-- resources/views/articles/show.blade.php --}}

@php
    $featuredImage = $article->featured_image;
@endphp

<x-layouts.app
    :meta="[
        'title' => filled($article->meta_title)
            ? $article->meta_title
            : $article->title,

        'description' => Str::limit(
            strip_tags(
                filled($article->meta_description)
                    ? $article->meta_description
                    : $article->description
            ),
            200
        ),

        'image' => $featuredImage 
            ? Storage::url($featuredImage->path) 
            : asset('images/default-article.jpg'),
    ]"
>
    
       
    <x-ui.card class="max-w-4xl mx-auto flex flex-col items-center prose-content shadow-none! bg-transparent! border-none! ">


        {{-- PUBLISHED DATE --}}

        <div class="mt-5 text-xs font-light uppercase tracking-widest">
            <span>{{ $article->published_at->format('M d Y') }}</span>
        </div>


        {{-- TITLE AND EXCERPT --}}

        <h1 class="text-center mt-2 mb-0">
            {{ $article->title }}
        </h1>

        <p class="text-center mt-3 mb-3! font-heading text-xl font-light">
            {{ $article->excerpt }}
        </p>


        {{-- CATEGORY/AUTHOR PIPS --}}

        <span>
            <x-ui.category-pip
                href="{{route('categories.show', $article->category->slug)}}"
                color="{{$article->category->color}}"
            >
                {{$article->category->name}}
            </x-ui.category-pip>

            <a class="inline-flex items-center rounded-full px-3 py-1 text-xs font-normal transition bg-red-500 text-white hover:bg-lime-20 no-underline">
                by {{$article->author->display_name}}
            </a>
        </span>

        
        {{-- FEATURED ARTICLE IMAGE --}}

        <picture>
            @if(Storage::disk('public')->exists($featuredImage->path . '.avif'))
                <source
                    srcset="{{ Storage::url($featuredImage->path . '.avif') }}"
                    type="image/avif">
            @endif

            @if(Storage::disk('public')->exists($featuredImage->path . '.webp'))
                <source
                    srcset="{{ Storage::url($featuredImage->path . '.webp') }}"
                    type="image/webp">
            @endif

            <img
                src="{{ Storage::url($featuredImage->path . '.jpg') }}"
                alt="{{ $featuredImage->alt_text }}"
                loading="lazy"
                decoding="async">
        </picture>


        @if ($featuredImage && $featuredImage->source)
            <div class="max-w-3xl flex flex-col gap-4 border border-zinc-300 w-full px-2.5 py-1.5 text-xs mb-1 bg-amber-50">
                <span>
                    Image source:
                    <a
                        href="{{ $featuredImage->source_url ?: '#' }}"
                        target="_blank"
                        class="link ml-1"
                    >
                        {{ $featuredImage->source }}
                        <x-ui.icon
                            name="arrow-top-right-on-square"
                            size="xs"
                            class="ml-1 relative -top-1 w-2!"
                        />
                    </a>
                </span>
            </div>
        @endif


        {{-- ARTICLE CONTENT --}}

        <article class="prose-content w-full max-w-3xl">
            {!! $article->contentHtml !!}
        </article>


    </x-ui.card>
    

</x-layouts.app>

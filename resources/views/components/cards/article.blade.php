@props([
    'title',
    'excerpt' => null,
    'featuredImage' => null,
    'authorName' => null,
    'publishedAt' => null,
    'url' => '#',
])

<article class="border border-gray-200 rounded-sm overflow-hidden bg-white hover:shadow-md transition-shadow duration-200">
        
    {{-- Featured image --}}
    <a href="{{ $url }}" class="block">
        <img 
            src="{{ $featuredImage ? asset($featuredImage->path) : asset('images/default-article.jpg') }}"
            class="w-full h-48 object-cover"
        >
    </a>

    {{-- Content --}}
    <div class="p-4 space-y-2">

        {{-- Title --}}
        <a href="{{ $url }}">
            <h2 class="text-lg font-semibold text-gray-900 leading-snug hover:text-blue-600 transition-colors">
                {{ $title }}
            </h2>
        </a>

        {{-- Excerpt --}}
        @if($excerpt)
            <p class="text-sm text-gray-600 line-clamp-3">
                {{ $excerpt }}
            </p>
        @endif

        {{-- Meta --}}
        @if($authorName || $publishedAt)
            <div class="flex items-center justify-between text-xs text-gray-500 pt-2">
                
                @if($authorName)
                    <span>By {{ $authorName }}</span>
                @endif

                @if($publishedAt)
                    <span>{{ $publishedAt }}</span>
                @endif

            </div>
        @endif

    </div>
</article>
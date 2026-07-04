@props([
    'title',
    'excerpt' => null,
    'featuredImage' => null,
    'authorName' => null,
    'views' => 0,
    'publishedAt' => null,
    'url' => '#',
])

<article class="border border-gray-200 rounded-sm overflow-hidden bg-white hover:shadow-md transition-shadow duration-200">
        
    {{-- Featured image --}}
    <a href="{{ $url }}" class="block">
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
                class="w-full h-48 object-cover"
                loading="eager"
                fetchpriority="high"
                decoding="async">
        </picture>
    </a>

    {{-- Content --}}
    <div class="p-4 space-y-2">

        {{-- Title --}}
        <a href="{{ $url }}">
            <h2 class="text-lg font-semibold text-gray-900 leading-snug hover:text-blue-600 transition-colors mb-2">
                {{ $title }}
            </h2>
        </a>

        {{-- Excerpt --}}
        @if($excerpt)
            <p class="text-sm text-gray-600 line-clamp-3">
                {{ $excerpt }}
            </p>
        @endif

        {{-- Publishing --}}
        @if($authorName || $publishedAt)
            <div class="flex items-center justify-between text-xs text-gray-500 pt-2">
                
                @if($authorName)
                    {{-- <span>By {{ $authorName }}</span> --}}
                @endif

                @if($publishedAt)
                    <span>{{ $publishedAt->format('M d Y') }}</span>
                @endif

                <span class="inline-flex items-center gap-1 text-xs text-zinc-500">
                    {{ number_format($views) }} views
                </span>

            </div>
        @endif

    </div>
</article>
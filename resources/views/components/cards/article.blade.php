@props([
    'title',
    'excerpt' => null,
    'featuredImage' => null,
    'authorName' => null,
    'views' => 0,
    'publishedAt' => null,
    'url' => '#',
    'sideList' => false,
])


@if($sideList)
    <article class="border-b border-gray-200 overflow-hidden bg-white hover:shadow-md transition-shadow duration-200 m-2">
@else
    <article class="border border-gray-200 rounded-sm overflow-hidden bg-white hover:shadow-md transition-shadow duration-200">
@endif
        
    {{-- Featured image --}}
    <a href="{{ $url }}" class="block p-2 pb-0">
        <x-ui.image
            :image="$featuredImage"
            class="w-full sm:w-[480px] h-48 object-cover rounded-tl-sm rounded-tr-sm rounded-bl-none rounded-br-none"
            sizes="(min-width: 640px) 480px, 100vw"
        />
    </a>

    {{-- Content --}}
    <div class="p-4 space-y-2">

        {{-- Title --}}
        <a href="{{ $url }}">
            <h2 class="text-lg font-semibold text-gray-900 leading-snug hover:text-red-metrix transition-colors mb-2">
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
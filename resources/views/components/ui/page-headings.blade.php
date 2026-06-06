{{-- resources/views/components/ui/page-heading.blade.php --}}

@if(!empty($title) || !empty($subtitle))


    @if (request()->routeIs('contact.show'))
        <div 
            class="mb-4 py-10 relative text-center"
        >
            @if($title)
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-700">
                    {!! $title !!}
                </h1>
            @endif

            @if($subtitle)
                <p class="mt-3 text-base sm:text-lg font-light text-zinc-600 max-w-2xl mx-auto">
                    {!! $subtitle !!}
                </p>
            @endif
        </div>
            
    @else

        {{-- Without banner image --}}
        <div 
            class="mb-10 py-10 bg-zinc-200 border-b border-gray-300 relative text-center"
        >

        {{-- With banner image --}}
        {{-- <div 
            class="mb-10 py-10 bg-zinc-200 border-b border-gray-300 relative text-center" 
            style="background-image: url('images/banner.png'); background-size:cover; background-position: left -240px;"
        > --}}

            @if($title)
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-700">
                    {!! $title !!}
                </h1>
            @endif

            @if($subtitle)
                <p class="mt-3 text-base sm:text-lg text-zinc-600 max-w-2xl mx-auto">
                    {!! $subtitle !!}
                </p>
            @endif

            {{-- Centered accent line --}}
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 w-16 bg-olive-600"></div>

        </div>
    @endif
@endif
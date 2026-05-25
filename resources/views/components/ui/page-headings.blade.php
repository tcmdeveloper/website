{{-- resources/views/components/ui/page-heading.blade.php --}}

@if(!empty($title) || !empty($subtitle))

    <div class="my-10 pb-10 border-b border-gray-200 relative text-center">

        @if($title)
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">
                {!! $title !!}
            </h1>
        @endif

        @if($subtitle)
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                {!! $subtitle !!}
            </p>
        @endif

        {{-- Centered accent line --}}
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 w-16 bg-olive-600"></div>

    </div>

@endif
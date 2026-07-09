{{-- resources/views/components/ui/page-heading.blade.php --}}

@if(!empty($title) || !empty($subtitle))


    @if (! request()->routeIs('home'))


        {{-- Without banner image --}}
        <div
            class="mb-10 relative overflow-hidden border-b border-gray-300"
        >
            <!-- Background image -->
            <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('images/hero-default.jpg') }}');"
            ></div>

            <!-- Black overlay -->
            <div class="absolute inset-0 bg-black/80"></div>

            <!-- Content -->
            <div class="relative z-10 px-3 py-6 text-center">
                @if($title)
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-yellow-300">
                        {!! $title !!}
                    </h1>
                @endif

                @if($subtitle)
                    <p class="mt-3 mb-2 text-base sm:text-lg text-white max-w-2xl mx-auto">
                        {!! $subtitle !!}
                    </p>
                @endif

              
            </div>
        </div>
            
    @else

        {{-- Without banner image --}}
        <div
            class="mb-10 relative overflow-hidden border-b border-gray-300"
        >
            <!-- Background image -->
            <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('images/hero-default.jpg') }}');"
            ></div>

            <!-- Black overlay -->
            <div class="absolute inset-0 bg-black/80"></div>

            <!-- Content -->
            <div class="relative z-10 px-3 py-12 text-center">
                @if($title)
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-yellow-300">
                        {!! $title !!}
                    </h1>
                @endif

                @if($subtitle)
                    <p class="mt-3 mb-5 text-base sm:text-lg text-white max-w-2xl mx-auto">
                        {!! $subtitle !!}
                    </p>
                @endif

                <x-ui.button
                    href="{{route('cases.index')}}"
                    size="xs"
                    variant="primary"
                >
                    Case Files
                </x-ui.button>
                {{-- <x-ui.button
                    href="{{route('cases.index')}}"
                    size="xs"
                    variant="ghost"
                >
                    Support Metrix
                </x-ui.button> --}}
            </div>
        </div>

        {{-- With banner image --}}
        {{-- <div 
            class="mb-10 py-10 bg-zinc-200 border-b border-gray-300 relative text-center" 
            style="background-image: url('images/banner.png'); background-size:cover; background-position: left -240px;"
        > --}}

            

            {{-- Centered accent line --}}
            {{-- <div class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 w-16 bg-olive-600"></div> --}}

        {{-- </div> --}}
        

    @endif
@endif
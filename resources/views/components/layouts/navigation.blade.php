{{-- resources/views/components/layouts/navigation.blade.php --}}

<nav
    x-data="{ menuOpen: false, searchOpen: false }"
    @click.away="searchOpen = false"
    class="
        fixed top-0 left-0 w-full h-[61px] z-50
        flex
        px-6 py-4
        bg-zinc-900 text-sm font-light
        border-b
        border-b-stone-400
        shadow-xl
    "
>

    {{-- Left side --}}
    <div class="justify-self-start lg:w-80 md:w-64 w-16">
        <a href="/">
            <img
    src="{{ asset('images/true-crime-metrix-logo-xs.png') }}"
    alt="True Crime Metrix"
    class="h-7 w-7"
>
        </a>
    </div>

    {{-- Center --}}
    <div class="justify-self-center flex-1 text-center">
        <a href="/" class="group font-brand nav-link font-bold tracking-wide text-lg">
            <span
                class="
                    inline-block
                    transition-transform duration-300
                    group-hover:translate-y-[2px] group-hover:scale-[101%]
                "
            >
                True Crime Metrix
            </span>
        </a>
    </div>

    {{-- Right side --}}
    <div class="justify-self-end flex justify-end items-center gap-5 lg:w-80 md:w-64 w-16">

        {{-- Main menu button --}}
        <button
            @click="menuOpen = true; searchOpen = false"
            class="nav-link"
            type="button"
            aria-label="Open navigation menu"
            :aria-expanded="menuOpen"
            aria-controls="main-menu"
        >
            <x-ui.icon name="bars-3" />
        </button>

        {{-- Search button --}}
        <button
            @click="searchOpen = !searchOpen; menuOpen = false"
            class="nav-link md:inline-flex hidden"
            type="button"
            aria-label="Toggle search"
            :aria-expanded="searchOpen"
            aria-controls="search-dropdown"
        >
            <x-ui.icon name="magnifying-glass" />
        </button>

        @auth
            <a href="{{ route('dashboard.index') }}" class="nav-link md:inline-flex hidden">
                Dashboard
            </a>

            <form method="POST" action="{{ route('logout') }}" class="md:inline-flex hidden">
                @csrf

                <x-ui.text-button type="submit" class="nav-link">
                    Log out
                </x-ui.text-button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link md:inline-flex hidden">
                Sign in
            </a>
        @endauth

    </div>

    {{-- BACKDROP --}}
    <div
        x-cloak
        x-show="menuOpen"
        x-transition.opacity
        class="fixed inset-0 bg-zinc-900/95 z-40"
        @click="menuOpen = false"
        aria-hidden="true"
    ></div>

    {{-- FULL SCREEN DRIFT MENU --}}
    <div
        id="main-menu"
        x-cloak
        class="fixed inset-0 z-50"
        x-show="menuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        role="dialog"
        aria-modal="true"
        aria-label="Navigation menu"
    >

        {{-- Top row --}}
        <div class="h-[61px] pr-10 flex justify-end items-center border-b border-zinc-600">
            <x-ui.text-button
                @click="menuOpen = false"
                class="text-zinc-300 hover:text-white text-4xl!"
                aria-label="Close navigation menu"
            >
                ✕
            </x-ui.text-button>
        </div>

        {{-- Menu items --}}
        <div class="w-full p-8 mt-32 flex flex-col items-center space-y-5 text-5xl md:text-6xl font-heading font-bold">
            <a href="{{ route('cases.index') }}" class="whitespace-nowrap text-zinc-100 hover:text-yellow-300">
                Cases
            </a>

            <a href="{{ route('articles.index') }}" class="whitespace-nowrap text-zinc-100 hover:text-yellow-300">
                Articles
            </a>

            <a href="{{ route('categories.index') }}" class="whitespace-nowrap text-zinc-100 hover:text-yellow-300">
                Categories
            </a>

            <a href="{{ route('contact.show') }}" class="whitespace-nowrap text-zinc-100 hover:text-yellow-300">
                Contact me
            </a>
        </div>

    </div>

    {{-- SEARCH DROPDOWN --}}
    <div
        id="search-dropdown"
        x-cloak
        x-show="searchOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="absolute top-full left-0 w-full bg-stone-300 border-b border-b-stone-400 shadow-xl px-6 py-4 z-40"
    >
        <form action="/search" method="GET" class="max-w-3xl mx-auto">

            <div>
                <x-ui.input
                    name="q"
                    type="text"
                    label="Search"
                    hideLabel="true"
                    placeholder="Search cases, documents..."
                    class="text-black font-body"
                    autofocus
                />
            </div>

        </form>
    </div>

</nav>
<nav {{ $attributes->merge(['class' => 'w-full border-b border-gray-200 bg-white shadow-sm font-display font-medium']) }}>
    <div class="mx-auto flex max-w-7xl items-center justify-center">
        <ul class="flex items-center space-x-2 py-3">
            <li>
                <a href="{{ route('cases.index') }}"
                   class="rounded-md px-4 py-2 text-sm font-medium transition-colors
                   {{ request()->routeIs('cases.*')
                        ? 'bg-red-metrix text-white'
                        : 'text-zinc-800 hover:bg-zinc-800 hover:text-white' }}">
                    Case Files
                </a>
            </li>

            <li>
                <a href="{{ route('categories.index') }}"
                   class="rounded-md px-4 py-2 text-sm font-medium transition-colors
                   {{ request()->routeIs('categories.*')
                        ? 'bg-red-metrix text-white'
                        : 'text-zinc-800 hover:bg-zinc-800 hover:text-white' }}">
                    Categories
                </a>
            </li>

            <li>
                <a href="{{ route('articles.index') }}"
                   class="rounded-md px-4 py-2 text-sm font-medium transition-colors
                   {{ request()->routeIs('articles.*')
                        ? 'bg-red-metrix text-white'
                        : 'text-zinc-800 hover:bg-zinc-800 hover:text-white' }}">
                    Articles
                </a>
            </li>

           

            <li>
                <a href="{{ route('timelines.index') }}"
                   class="rounded-md px-4 py-2 text-sm font-medium transition-colors
                   {{ request()->routeIs('timelines.*')
                        ? 'bg-red-metrix text-white'
                        : 'text-zinc-800 hover:bg-zinc-800 hover:text-white' }}">
                    Timelines
                </a>
            </li>
        </ul>
    </div>
</nav>
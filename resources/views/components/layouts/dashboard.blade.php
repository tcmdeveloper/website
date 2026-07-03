<x-layouts.app>


    <div class="flex min-h-screen bg-stone-100">

        {{-- Sidebar --}}
        <aside class="w-72 bg-white border-r border-zinc-200 flex flex-col">

            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-zinc-200">
                <a href="{{ route('dashboard.index') }}" class="text-xl font-bold text-stone-800">
                    Metrix Admin
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-8">

                {{-- Dashboard --}}
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold tracking-wider uppercase text-stone-400">
                        Overview
                    </p>

                    <div class="space-y-1">

                        <a
                            href="{{ route('dashboard.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('dashboard'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('dashboard'),
                            ])
                        >
                            <x-heroicon-o-home class="w-5 h-5" />
                            Dashboard
                        </a>

                    </div>
                </div>


                {{-- Content --}}
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold tracking-wider uppercase text-stone-400">
                        Content
                    </p>

                    <div class="space-y-1">

                        <a
                            href="{{ route('admin.criminal-cases.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('admin.categories.*'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('admin.categories.*'),
                            ])
                        >
                            <x-heroicon-o-finger-print class="w-5 h-5" />
                            Criminal cases
                        </a>

                        <a
                            href="{{ route('admin.categories.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('admin.categories.*'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('admin.categories.*'),
                            ])
                        >
                            <x-heroicon-o-folder class="w-5 h-5" />
                            Categories
                        </a>

                        <a
                            href="{{ route('admin.articles.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('admin.articles.*'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('admin.articles.*'),
                            ])
                        >
                            <x-heroicon-o-document-text class="w-5 h-5" />
                            Articles
                        </a>

                        <a
                            href="{{ route('admin.documents.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('admin.documents.*'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('admin.documents.*'),
                            ])
                        >
                            <x-heroicon-o-document-text class="w-5 h-5" />
                            Documents
                        </a>

                        <a
                            href="{{ route('admin.videos.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('admin.videos.*'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('admin.videos.*'),
                            ])
                        >
                            <x-heroicon-o-film class="w-5 h-5" />
                            Videos
                        </a>

                    </div>
                </div>


                {{-- Users --}}
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold tracking-wider uppercase text-stone-400">
                        Administration
                    </p>

                    <div class="space-y-1">

                        {{-- <a
                            href="{{ route('admin.users.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('admin.users.*'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('admin.users.*'),
                            ])
                        >
                            <x-heroicon-o-users class="w-5 h-5" />
                            Users
                        </a> --}}

                        <a
                            href="{{ route('profile.edit') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-stone-900 text-white' => request()->routeIs('profile.*'),
                                'text-stone-700 hover:bg-stone-100' => !request()->routeIs('profile.*'),
                            ])
                        >
                            <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                            Settings
                        </a>

                    </div>
                </div>

            </nav>


            {{-- User --}}
            <div class="border-t border-zinc-200 p-4">

                <div class="mb-4">
                    <p class="text-sm font-medium text-stone-800">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-stone-500">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-100 transition"
                    >
                        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" />
                        Logout
                    </button>
                </form>

            </div>

        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>

    </div>


</x-layouts.app>
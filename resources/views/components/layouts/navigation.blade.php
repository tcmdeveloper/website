{{-- resources/views/components/layouts/navigation.blade.php --}}

<nav class="bg-white border-b">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <a href="/" class="text-xl font-bold text-gray-800">
                {{ config('app.name') }}
            </a>

            {{-- Links --}}
            <div class="flex gap-6 text-sm font-medium text-gray-600">
                <a href="/" class="hover:text-gray-900">Home</a>
                <a href="/about" class="hover:text-gray-900">About</a>
                <a href="/contact" class="hover:text-gray-900">Contact</a>
                @auth
                    <a href="/dashboard" class="hover:text-gray-900">Dashboard</a>
                    <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <x-ui.text-button type="submit">
                                Log out
                            </x-ui.text-button>
                            
                        </form>
                @else
                    
                    <a href="/login" class="hover:text-gray-900">Log in</a>
                    <a href="/register" class="hover:text-gray-900">Register</a>
                @endauth
            </div>

        </div>
    </div>
</nav>
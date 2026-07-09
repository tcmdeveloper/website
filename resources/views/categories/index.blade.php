{{-- resources/views/categories/index.blade.php --}}

<x-layouts.app
    title="Categories"
    subtitle="Explore posts organized by topic"
>

    <x-ui.container class="max-w-5xl">



        {{-- Categories Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            @forelse($categories as $category)

                <a
                    href="{{ route('categories.show', $category->slug) }}"
                    class="group block rounded-xl border border-zinc-200 p-6 hover:shadow-md hover:border-red-metrix transition bg-white"
                >

                    {{-- Category name --}}
                    <h2 class="text-lg font-semibold group-hover:text-red-metrix transition">
                        {{ $category->name }}
                    </h2>

                    {{-- Description --}}
                    @if($category->description)
                        <p class="text-sm text-zinc-500 mt-2">
                            {{ Str::limit($category->description, 300) }}
                        </p>
                    @endif

                    {{-- Meta --}}
                    <div class="mt-4 flex items-center justify-between text-sm text-zinc-400">

                        <span>
                            {{ $category->articles_count }} posts
                        </span>

                        <span class="group-hover:text-red-metrix transition">
                            View →
                        </span>

                    </div>

                </a>

            @empty

                <div class="col-span-full text-center py-16 text-zinc-500">
                    No categories found.
                </div>

            @endforelse

        </div>

    </x-ui.container>

</x-layouts.app>
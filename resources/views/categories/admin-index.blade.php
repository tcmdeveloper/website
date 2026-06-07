{{-- resources/views/admin/categories/index.blade.php --}}

<x-layouts.app
    title="Categories"
    subtitle="Manage your content categories."
>

    <div class="max-w-6xl mx-auto">

        {{-- Header Actions --}}
        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-semibold">
                    Categories
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Organize your content into categories.
                </p>
            </div>

            <x-ui.button
                href="{{ route('categories.create') }}"
            >
                New Category
            </x-ui.button>

        </div>

        {{-- Search --}}
        <div class="mb-6">
            <input
                type="text"
                placeholder="Search categories..."
                class="w-full rounded-lg border border-zinc-300 px-4 py-2"
            >
        </div>

        {{-- Table --}}
        <x-ui.card class="overflow-hidden p-0">

            <table class="w-full">

                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-sm text-zinc-600">

                        <th class="px-6 py-4 font-medium">
                            Name
                            
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Slug
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Articles
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Created
                        </th>

                        <th class="px-6 py-4 w-32"></th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr class="border-b border-zinc-100">

                            <td class="px-6 pl-0 py-4 font-medium">
    <div class="flex items-stretch">
        <div class="w-2 bg-{{ $category->color }}-500 mr-6"></div>

        <span>
            {{ $category->name }}
        </span>
    </div>
</td>

                            <td class="px-6 py-4 text-zinc-500">
                                {{ $category->slug }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $category->article_count }}
                            </td>

                            <td class="px-6 py-4 text-zinc-500">
                                {{ $category->created_at->format('M j, Y') }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <x-ui.button
                                        size="xs"
                                        variant="ghost"
                                        href="{{ route('categories.edit', $category) }}"
                                    >
                                        Edit
                                    </x-ui.button>

                                    <form
                                        method="POST"
                                        action="{{ route('categories.destroy', $category) }}"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <x-ui.button
                                            size="xs"
                                            variant="danger"
                                        >
                                            Delete
                                        </x-ui.button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-zinc-500"
                            >
                                No categories found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </x-ui.card>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $categories->links() }}
        </div>

    </div>

</x-layouts.app>
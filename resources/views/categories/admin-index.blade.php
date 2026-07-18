{{-- resources/views/categories/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Categories"
            subtitle="Manage the content categories for this site."
            :href="route('admin.categories.create')"
            label="New category"
        />


        {{-- Table --}}

        <table class="w-full border text-xs">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Articles</th>
                    <th class="px-6 py-4">Created</th>
                    <th class="px-6 py-4 w-32"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($categories as $category)

                    <tr class="border-b border-zinc-100">

                        <td class="px-6 pl-0 py-4 font-medium">
                            <div class="flex items-stretch">
                                <div class="w-2 {{ $category->colorClass() }} mr-6"></div>

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
                                    href="{{ route('admin.categories.edit', $category) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <x-ui.button
                                        type="submit"
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


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $categories->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>
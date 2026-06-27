{{-- resources/views/articles/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Articles"
            subtitle="Manage the articles for this site."
            :href="route('admin.articles.create')"
            label="New article"
        />


        {{-- Alert --}}
        <x-ui.alert />
        

        {{-- Table --}}

        <table class="w-full border">

            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-sm text-zinc-600">

                    <th class="px-6 py-4 font-medium">
                        Title
                        
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Category
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Views
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Created
                    </th>

                    <th class="px-6 py-4 w-32"></th>

                </tr>
            </thead>


            <tbody>

                @forelse($articles as $article)

                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $article->title }}
                        </td>


                        <td class="px-6 py-4 text-zinc-500">
                            {{ $article->category->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $article->views }}
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $article->created_at->format('M j, Y') }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.articles.edit', $article) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.articles.images', $article) }}"
                                >
                                    Images
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.articles.destroy', $article) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this article? This action cannot be undone.')"
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
                            No articles found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $articles->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>

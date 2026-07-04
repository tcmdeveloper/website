{{-- resources/views/articles/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Articles"
            subtitle="Manage the articles for this site."
            :href="route('admin.articles.create')"
            label="New article"
        />


        <x-ui.alert />
        

        <table class="w-full border text-sm">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4 max-w-[12rem]">Title</th>
                    <th class="px-6 py-4">Visibility</th>
                    <th class="px-6 py-4">Case</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Views</th>
                    <th class="px-6 py-4">Created</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>


            <tbody>

                @forelse($articles as $article)

                    <tr class="border-b border-zinc-100 text-zinc-500">

                        <td class="px-6 py-4">
                            <div class="max-w-md line-clamp-2">
                                {{ $article->title }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($article->isPublished())
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">
                                    Public
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    Private
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <a href="{{ url('admin/articles?case=' . $article->criminalCase?->slug) }}" class="text-sky-800 hover:text-green-600">
                                {{ $article->criminalCase?->name ?? '...' }}
                            </a>
                        </td>

                        <td class="px-6 py-4">
                            {{ $article->category?->name ?? '...' }}
                        </td>

                        <td class="px-6 py-4">
                           {{ number_format($article->formatted_views) }}
                        </td>

                        <td class="px-6 py-4">
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

        <div class="mt-6 flex justify-end">
            {{ $articles->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>

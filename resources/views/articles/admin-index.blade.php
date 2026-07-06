{{-- resources/views/articles/admin-index.blade.php --}}

{{-- ACTION BUTTONS --}}

@php
    $actions = [
        'create' => [
            'label' => 'Create New Article',
            'href' => route('admin.articles.create', request()->only('case')),
            'variant' => 'primary',
        ]
    ];

    // Add back button if results are filtered
    if (request()->filled('case') || request()->filled('category')) {
        $actions = array_merge([
            'back' => [
                'label' => 'Back to Articles',
                'href' => route('admin.articles.index'),
                'variant' => 'ghost',
            ],
        ], $actions);
    }
@endphp

<x-layouts.dashboard>
    
    <x-ui.card>

        <x-ui.header-actions
            :title="$title ?? 'True Crime Articles'"
            :subtitle="$subtitle ?? 'Manage and organize your true crime articles.'"
            :actions="$actions"
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

                        <td class="px-6 py-4 flex gap-4 items-center">

                            <x-ui.image
                                :image="$article->display_image"
                                class="w-20"
                            />

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
                            <a 
                                href="{{ url('admin/articles?case=' . $article->criminalCase?->slug) }}" 
                                class="text-sky-800 hover:text-green-600"
                            >
                                {{ $article->criminalCase?->name ?? '...' }}
                            </a>
                        </td>

                        <td class="px-6 py-4">
                            <a 
                                href="{{ url('admin/articles?category=' . $article->category?->slug) }}" 
                                class="text-sky-800 hover:text-green-600"
                            >
                                {{ $article->category?->name ?? '...' }}
                            </a>
                           
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
                            colspan="7"
                            class="px-6 py-12 text-center text-zinc-500"
                        >
                            No articles found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        @if(method_exists($articles, 'links'))

            <div class="mt-6 flex justify-end">
                {{ $articles->links() }}
            </div>

        @endif


    </x-ui.card>


</x-layouts.dashboard>

{{-- resources/views/criminal-cases/images-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Manage Article Images"
            subtitle="Upload, organize, and manage the images used in this article."
            :actions="[
                'back' => [
                    'label' => 'Back to Articles',
                    'href' => route('admin.articles.index'),
                    'variant' => 'ghost',
                ],
                'uploadImage' => [
                    'label' => 'Upload New Image',
                    'href' => route('admin.articles.images.create', $article),
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-sm">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4">Image</th>
                    <th class="px-6 py-4">Caption</th>
                    <th class="px-6 py-4">Alt text</th>
                    <th class="px-6 py-4">Featured</th>
                    <th class="px-6 py-4">Created at</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>


            <tbody>

                @forelse ($images as $image)
                    
                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 flex gap-4 items-center">
                            <x-ui.image
                                :image="$image"
                                class="w-20"
                            />
                        </td>

                        <td class="px-6 py-4">{{ Str::limit($image->caption, 50) }}</td>

                        <td class="px-6 py-4">{{ Str::limit($image->alt_text, 50) }}</td>

                        <td class="px-6 py-4">{{ $image->is_featured ? 'Featured' : '-' }}</td>

                        <td class="px-6 py-4">{{ $image->created_at->format('d M Y') }}</td>

                        <td class="px-6 py-4">
                           <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.articles.images.edit', [$article, $image]) }}"    
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.articles.images.destroy', [$article, $image]) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this image? This cannot be undone.')"
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
                            No articles images.
                        </td>

                    </tr>

                @endforelse



            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6 flex justify-end">
            {{ $images->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>

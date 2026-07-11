{{-- resources/views/criminal-cases/images-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Manage Case Images"
            subtitle="Upload, organize, and manage the images used in this criminal case."
            :actions="[
                'back' => [
                    'label' => 'Back to Criminal Cases',
                    'href' => route('admin.criminal-cases.index'),
                    'variant' => 'ghost',
                ],
                'uploadImage' => [
                    'label' => 'Upload New Image',
                    'href' => route('admin.criminal-cases.images.create', $criminalCase),
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
                    <th class="px-6 py-4">Multi-Format</th>
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
                                sizes="80px"
                            />
                        </td>

                        <td class="px-6 py-4">{{ Str::limit($image->caption, 50) }}</td>

                        <td class="px-6 py-4">{{ Str::limit($image->alt_text, 50) }}</td>

                        <td class="px-6 py-4">{{ $image->is_featured ? 'Featured' : '-' }}</td>

                        <td class="px-6 py-4">
                            @if ($image->has_multiformat)
                                <span class="text-green-600">✓ Optimized</span>
                            @else
                                {{-- <span class="text-amber-600">⚠ Needs optimization</span> --}}
                            @endif
                        </td>

                        <td class="px-6 py-4">{{ $image->created_at->format('d M Y') }}</td>

                        <td class="px-6 py-4">
                           <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.criminal-cases.images.edit', [$criminalCase, $image]) }}"    
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.criminal-cases.images.destroy', [$criminalCase, $image]) }}"
                                    onsubmit="return confirm('Delete this image?')"
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
                            colspan="6"
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

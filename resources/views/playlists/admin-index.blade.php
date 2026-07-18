{{-- resources/views/playlists/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            :title="$title ?? 'Playlists'"
            :subtitle="$subtitle ?? 'Manage and organize your playlists.'"
            :actions="[
                'create' => [
                    'label' => 'Create New Playlist',
                    'href' => route('admin.playlists.create'),
                    'variant' => 'primary',
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-xs">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4 w-[24rem]">Name</th>
                    <th class="px-6 py-4">Criminal Case</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4 text-center">Views</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($playlists as $playlist)
                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 flex gap-4 items-center">

                            <x-ui.image
                                :image="$playlist->display_image"
                                class="w-20"
                                sizes="(min-width: 640px) 80px, 100vw"
                                is_optimized="{{ $playlist->display_image->is_optimized ?? true }}"
                            />

                            <div class="max-w-md line-clamp-2 font-medium">
                                {{ $playlist->name }}
                            </div>

                        </td>

                        <td class="px-6 py-4">

                            @foreach($playlist->criminalCases as $criminalCase)
                                {{ $criminalCase->name}}
                            @endforeach

                        </td>

                        <td class="px-6 py-4">
                            {{ $playlist->description }}
                        </td>

       

                        <td class="px-6 py-4 text-center">
                            {{ number_format($playlist->views) }}
                        </td>

                     
                        

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.playlists.edit', $playlist) }}"
                                >
                                    Edit
                                </x-ui.button>

        
                                
                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.playlists.videos.index', $playlist) }}"
                                >
                                    Videos
                                </x-ui.button>


                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.playlists.images.index', $playlist) }}"
                                >
                                    Thumbnail
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.playlists.destroy', $playlist) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this playlist? This action cannot be undone.')"
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
                            No playlists have been added.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $playlists->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>
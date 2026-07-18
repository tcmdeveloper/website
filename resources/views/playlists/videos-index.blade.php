{{-- resources/views/playlists/images-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Manage Playlist Videos"
            subtitle="Upload, organize, and manage the videos in this playlist."
            :actions="[
                'back' => [
                    'label' => 'Back to Playlists',
                    'href' => route('admin.playlists.index'),
                    'variant' => 'ghost',
                ],
                'editPlaylistVideos' => [
                    'label' => 'Edit Playlist Videos',
                    'href' => route('admin.playlists.videos.edit', $playlist),
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-xs">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4 text-center">Duration</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>


            <tbody>

                @forelse ($playlistVideos as $video)
                    
                    <tr class="border-b border-zinc-100 text-zinc-500">
                        
                        <td class="px-6 py-4 flex gap-4 items-center">

                            <img
                                src="{{ empty($video->thumbnail) ? Storage::url('images/default-article.jpg') : Storage::url($video->thumbnail) }}"
                                class="w-20 aspect-video rounded object-cover"
                                alt="{{ $video->title }}"
                            >

                            <div class="max-w-md line-clamp-2">
                                {{ $video->title }}
                            </div>

                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ $video->duration_formatted }}
                        </td>

                        <td class="px-6 py-4 text-center">

                            @if ($video->hasTranscript())
                                <x-ui.pip label="Transcribed" variant="success" />
                            @elseif ($video->isTranscribing())
                                <x-ui.pip label="Transcribing..." variant="warning" />
                            @elseif ($video->isDownloaded())
                                <x-ui.pip label="Downloaded" variant="info" />
                            @elseif ($video->isDownloading())
                                <x-ui.pip label="Downloading" variant="secondary" />
                            @elseif ($video->transcriptionFailed())
                                <x-ui.pip label="Failed" variant="danger" />
                            @endif
                            
                        </td>                        

                       

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.videos.edit', $video) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.videos.destroy', $video) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this video? This action cannot be undone.')"
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
                            No videos found.
                        </td>

                    </tr>

                @endforelse



            </tbody>

        </table>


        {{-- Pagination --}}

        {{-- <div class="mt-6 flex justify-end">
            {{ $playlistVideos->links() }}
        </div> --}}


    </x-ui.card>


</x-layouts.dashboard>

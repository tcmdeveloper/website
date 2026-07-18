{{-- resources/views/playlists/edit-videos.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Manage Playlist Videos"
            subtitle="Drag videos between columns and reorder the playlist."
            :actions="[
                'back' => [
                    'label' => 'Back to Playlist',
                    'href' => route('admin.playlists.index'),
                    'variant' => 'ghost',
                ],
            ]"
        />

        <x-ui.alert />

        <form
            method="POST"
            action="{{ route('admin.playlists.videos.update', $playlist) }}"
            x-data="playlistManager(
                @js(
                    $availableVideos->map(fn ($video) => [
                        'id' => $video->id,
                        'title' => $video->title,
                    ])
                ),
                @js(
                    $playlistVideos->map(fn ($video) => [
                        'id' => $video->id,
                        'title' => $video->title,
                    ])
                )
            )"
            class="space-y-6"
            @submit="serialize()"
        >
            @csrf

            @method('PATCH')


            <div class="grid grid-cols-2 gap-6">

                {{-- Available videos --}}

                <div>

                    <h3 class="mb-3 text-lg font-semibold">
                        Available Videos
                    </h3>

                    <div
                        x-ref="available"
                        class="space-y-2 rounded-lg border border-zinc-200 bg-zinc-50 p-4 min-h-[600px]"
                    >
                        <template
                            x-for="video in availableVideos"
                            :key="video.id"
                        >
                            <div
                                class="cursor-move rounded border border-zinc-200 bg-white p-3 shadow-sm"
                                :data-id="video.id"
                            >
                                <div class="font-medium" x-text="video.title"></div>
                            </div>
                        </template>
                    </div>

                </div>

                {{-- Playlist videos --}}

                <div>

                    <h3 class="mb-3 text-lg font-semibold">
                        Playlist
                    </h3>

                    <div
                        x-ref="playlist"
                        class="space-y-2 rounded-lg border border-zinc-200 bg-zinc-50 p-4 min-h-[600px]"
                    >
                        <template
                            x-for="video in playlistVideos"
                            :key="video.id"
                        >
                            <div
                                class="cursor-move rounded border border-zinc-200 bg-white p-3 shadow-sm"
                                :data-id="video.id"
                            >
                                <div class="font-medium" x-text="video.title"></div>

                                
                            </div>
                        </template>
                    </div>

                </div>

            </div>

            <div x-ref="hiddenInputs"></div>

            <div class="flex justify-end">

                <x-ui.button type="submit">
                    Save Playlist
                </x-ui.button>

            </div>

        </form>

    </x-ui.card>

</x-layouts.dashboard>

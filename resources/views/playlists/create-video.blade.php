{{-- resources/views/playlists/create-video.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Manage Videos in Playlist"
            subtitle="Add, remove, reorder the videos in this playlist."
            :actions="[
                'back' => [
                    'label' => 'Back to Playlist',
                    'href' => route('admin.playlists.videos.index', $playlist),
                    'variant' => 'ghost',
                ],
            ]"
        />

        <x-ui.alert />

        <form
            id="imageForm"
            method="POST"
            action="{{ route('admin.playlists.videos.store', $playlist) }}"
            x-data="imageUploader(
                '',
                false
            )"
            @submit.prevent="submit"
            class="space-y-6"
        >
            @csrf


            {{-- Caption --}}

            <div>
                <x-ui.textarea
                    name="caption"
                    type="text"
                    label="Image caption"
                    :value="old('caption')"
                    placeholder="Describe the image"
                />
            </div>


            {{-- Alt text --}}

            <div>
                <x-ui.input
                    name="alt_text"
                    type="text"
                    label="Alt text"
                    :value="old('alt_text')"
                    placeholder="Description for SEO"
                />
            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                {{-- Credit name --}}

                <div>
                    <x-ui.input
                        name="credit_name"
                        type="text"
                        label="Credit source"
                        :value="old('credit_name')"
                        placeholder="Name of source"
                    />
                </div>


                {{-- Credit URL --}}

                <div>
                    <x-ui.input
                        name="credit_url"
                        type="text"
                        label="Credit URL"
                        :value="old('credit_url')"
                        placeholder="URL of source"
                    />
                </div>

            </div>


            <div>

                <x-ui.button 
                    type="submit"
                    size="sm"
                >
                    <x-heroicon-o-check-circle class="w-4 h-4" />
                    Save Image
                </x-ui.button>

            </div>

        </form>

    </x-ui.card>

</x-layouts.dashboard>
{{-- resources/views/characters/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Create a New Playlist"
            subtitle="Enter the details for this new playlist."
            :actions="[
                'back' => [
                    'label' => 'Back to Playlists',
                    'href' => route('admin.playlists.index'),
                    'variant' => 'ghost',
                ]
            ]"
        />

        <x-ui.alert />

        <form 
            method="POST" 
            action="{{ route('admin.playlists.store') }}" 
            class="space-y-5"
        >

            @csrf

            {{-- Name --}}

            <div>
                <x-ui.input
                    name="name"
                    type="text"
                    label="Playlist name"
                    placeholder="Playlist name"
                    :value="old('name')"
                />
            </div>


            <div>
                <x-ui.select
                    name="criminal_case_id"
                    label="Criminal Case"
                    :options="$criminalCases->pluck('name', 'id')->toArray()"
                    :value="old('criminal_case_id')"
                    placeholder="Select a criminal case"
                />
            </div>
            

            {{-- Description --}}

            <div>
                <x-ui.textarea
                    name="description"
                    label="Description"
                    rows="5"
                    placeholder="Write a short description of this playlist..."
                >{{ old('description') }}</x-ui.textarea>
            </div>


            {{-- Visibility --}}

            <div>
                <x-ui.select
                    name="is_published"
                    label="Visibility"
                    :options="[
                        1 => 'Public',
                        0 => 'Private',
                    ]"
                    :value="old('is_published')"
                    placeholder="Set visibility"
                />
            </div>


            {{-- Submit form --}}

            <div class="flex justify-start gap-2">
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Create Playlist
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.playlists.index') }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>

    
    </x-ui.card>


</x-layouts.dashboard>
{{-- resources/views/videos/create.blade.php --}}


<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Download a YouTube video"
            subtitle="Enter the YouTube URL to download the video."
            :actions="[
                'back' => [
                    'label' => 'Back to Videos',
                    'href' => route('admin.videos.index'),
                    'variant' => 'ghost',
                ]
            ]"
        />


        {{-- Alert --}}
        <x-ui.alert />


        <form 
            method="POST" 
            action="{{ route('admin.videos.store') }}" 
            class="space-y-5"
        >

            @csrf

            <x-ui.input
                name="youtube_url"
                placeholder="https://www.youtube.com/watch?v=..."
            />


            {{-- Submit form --}}

            <div>
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Download video
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.videos.index') }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>


    </x-ui.card>


</x-layouts.dashboard>
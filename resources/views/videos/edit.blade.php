{{-- resources/views/videos/create.blade.php --}}

@push('scripts')

    <script src="https://www.youtube.com/iframe_api"></script>

    <script>
        let player = null;

        function initYouTubePlayer() {
            if (player) {
                player.destroy();
            }

            player = new YT.Player('player', {
                width: '100%',
                height: '100%',
                videoId: '{{ $video->youtube_id }}',
                playerVars: {
                    rel: 0,
                    modestbranding: 1,
                }
            });
        }

        window.onYouTubeIframeAPIReady = initYouTubePlayer;

        document.addEventListener('DOMContentLoaded', () => {
            // API already loaded?
            if (window.YT && YT.Player) {
                initYouTubePlayer();
            }
        });

        function seekTo(seconds) {
            if (!player) return;

            player.seekTo(seconds, true);
            player.playVideo();
        }
    </script>
@endpush


<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Manage video resources"
            subtitle="Generate transcription or create clips."
            :href="route('admin.videos.index')"
            label="All videos"
        />


        {{-- Alert --}}
        <x-ui.alert />


        <div class="space-y-6">

            {{-- Video player --}}

            <div class="aspect-video w-full">
                <div id="player" class="w-full h-full"></div>
            </div>


            {{-- Video info card --}}

            <div class="grid grid-cols-12 w-full bg-white rounded-xs shadow overflow-hidden">

                {{-- Thumbnail --}}
                <div class="col-span-3 relative">
                    <img
                        src="{{ Storage::url($video->thumbnail) }}"
                        alt="{{ $video->title }}"
                        class="w-full aspect-video"
                    >

                    {{-- Duration badge --}}
                    @if($video->duration)
                        <span class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
                            {{ gmdate('H:i:s', $video->duration) }}
                        </span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="col-span-9 p-4 py-2">

                    <h3 class="font-semibold text-gray-900 line-clamp-2">
                        {{ $video->title }}
                    </h3>

                    @if($video->uploader)
                        <p class="text-sm text-gray-500">
                            {{ $video->uploader }}
                        </p>
                    @endif

                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <x-ui.button
                            type="button"
                            size="xs"
                            href="{{route('admin.videos.transcribe', $video)}}"
                        >
                            Transcribe
                        </x-ui.button>
                    </div>

                </div>

            </div>


            <div class="prose-content">

                <form method="GET" class="mb-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search transcript..."
                        class="w-full rounded border px-3 py-2">
                </form>


                <div class="border rounded-lg overflow-y-auto max-h-[700px] divide-y">

                    @forelse($segments as $segment)

                        <button
                            type="button"
                            onclick="seekTo({{ (int) $segment->start }})"
                            class="w-full text-left p-3 hover:bg-gray-100 transition">

                            <div class="flex gap-3">

                                <span class="text-xs font-mono text-blue-600 shrink-0">
                                    {{ gmdate('i:s', $segment->start) }} - {{ gmdate('i:s', $segment->end) }}
                                </span>

                                <span class="text-sm">
                                    {!! $search
                                        ? str_ireplace(
                                            $search,
                                            '<mark class="bg-yellow-200">'.$search.'</mark>',
                                            e($segment->text)
                                        )
                                        : e($segment->text)
                                    !!}
                                </span>

                            </div>

                        </button>

                    @empty

                        <div class="p-4 text-gray-500">
                            No matching transcript found.
                        </div>

                    @endforelse

                </div>

            </div>

        
        </div>
        

    </x-ui.card>


</x-layouts.dashboard>
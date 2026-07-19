{{-- resources/views/video-presenter/present.blade.php --}}

@push('scripts')

    <script src="https://www.youtube.com/iframe_api"></script>

    <script>
        let player = null;

        function initYouTubePlayer() {
            if (player) {
                player.destroy();
            }

            player = new YT.Player('player', {
                width: '1920',
                height: '1024',
                
                videoId: 'btCUy8TzZDs',
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



<x-layouts.app
    :pageWithoutBranding="true"
    :meta="[
        'title' => 'Metrix Presenter',
        'description' => 'This is the Metrix Video Presenter. It\'s made by bears!'
    ]"
>


    <div class="absolute inset-0 overflow-hidden">

        {{-- Video --}}
        <div id="player" class="absolute inset-0 z-10"></div>

        {{-- Hover zone + sidebar --}}
        <div class="group absolute top-0 right-0 h-screen z-50">

            {{-- Invisible trigger area --}}
            <div class="absolute top-0 right-0 h-full w-12"></div>

            {{-- Sidebar --}}
            <aside class="
                absolute
                top-0
                right-0
                h-screen
                w-96
                bg-white/95
                shadow-2xl
                backdrop-blur
                translate-x-full
                transition-transform
                duration-300
                group-hover:translate-x-0
            ">

                <div class="p-6">

                    <h2 class="mb-4 text-xl font-bold">
                        Timeline
                    </h2>

                    <x-ui.button
                        type="button"
                        variant="primary"
                    >
                        This is a button
                    </x-ui.button>

                </div>

            </aside>

        </div>

    </div>


</x-layouts.app>
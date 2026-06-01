<x-layouts.app 
    title="Transcriptions"
    subtitle="Here are all the transcriptions you've been working on."
>

<form action="{{ route('transcriptions.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <x-ui.card class="mx-auto" x-data="{ mode: 'youtube' }">

        <!-- IMPORTANT: prevents flicker before Alpine loads -->
        <style>[x-cloak] { display: none !important; }</style>

        <!-- Toggle -->
        <div class="flex bg-gray-100 p-1 rounded-xl w-fit mx-auto mb-6">
            
            <button type="button"
                @click="mode = 'youtube'"
                :class="mode === 'youtube'
                    ? 'bg-blue-600 text-white shadow'
                    : 'text-gray-600'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition">
                🔗 YouTube
            </button>

            <button type="button"
                @click="mode = 'upload'"
                :class="mode === 'upload'
                    ? 'bg-blue-600 text-white shadow'
                    : 'text-gray-600'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition">
                📁 Upload
            </button>

        </div>

        <!-- FIXED HEIGHT CONTAINER (prevents layout jump) -->
        <div class="min-h-[140px]">

            <!-- YouTube -->
            <div
                x-cloak
                x-show="mode === 'youtube'"
                x-transition.opacity
                class="space-y-2"
            >
                <label class="text-sm font-medium text-gray-700">
                    YouTube URL
                </label>

                <input
                    type="url"
                    name="youtube_url"
                    placeholder="https://youtube.com/watch?v=..."
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-200"
                />
            </div>

            <!-- Upload -->
            <div
                x-cloak
                x-show="mode === 'upload'"
                x-transition.opacity
                class="space-y-2"
            >
                <label class="text-sm font-medium text-gray-700">
                    Upload file
                </label>

                <input
                    type="file"
                    name="file"
                    accept="audio/*,video/*"
                    class="w-full px-4 py-3 border rounded-lg bg-white"
                />
            </div>

        </div>

        <!-- Submit -->
        <div class="mt-6 flex justify-end">
            <button
                type="submit"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow">
                Start transcription
            </button>
        </div>

    </x-ui.card>
</form>

</x-layouts.app>
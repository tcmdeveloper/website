{{-- resources/views/articles/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Video downloads"
            subtitle="Manage videos you have downloaded from YouTube."
            :actions="[
                'create' => [
                    'label' => 'Download YouTube Video',
                    'href' => route('admin.videos.create'),
                    'variant' => 'primary',
                    'icon' => 'heroicon-o-play'
                ]
            ]"
        />


        {{-- Alert --}}
        <x-ui.alert />
        

        {{-- Table --}}

        <table class="w-full border">

            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-sm text-zinc-600">

                    <th class="px-6 py-4 font-medium">
                        Video ID
                        
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Title
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Duration
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Status
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Created
                    </th>

                    <th class="px-6 py-4 w-32"></th>

                </tr>
            </thead>


            <tbody>

                @forelse($videos as $video)

                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 text-zinc-500">
                            {{-- {{ $video->youtube_id }} --}}
                            <img
                                src="{{ $video->thumbnail ? Storage::url($video->thumbnail) : asset('images/default-video-thumbnail.jpg') }}"
                                class="w-24 aspect-video rounded object-cover"
                                alt="{{ $video->title }}"
                            >
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $video->title }}
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $video->duration_formatted }}
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $video->status }}
                        </td>                        

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $video->created_at->format('M j, Y') }}
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

        <div class="mt-6 flex justify-end">
            {{ $videos->links() }}
        </div>

    </x-ui.card>

</x-layouts.dashboard>
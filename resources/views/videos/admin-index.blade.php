{{-- resources/views/videos/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Video Manager"
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
       
        <x-ui.alert />
        


        <table class="w-full border text-sm">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4">Thumbnail</th>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Duration</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Created</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>


            <tbody>

                @forelse($videos as $video)

                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 text-zinc-500">
                            {{-- {{ $video->youtube_id }} --}}
                            <img
                                src="{{ empty($video->thumbnail) ? Storage::url('images/default-article.png') : Storage::url($video->thumbnail) }}"
                                class="w-20 aspect-video rounded object-cover"
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

                            <div>{{ ucfirst($video->status) }}</div>

                            @if($video->status === 'failed' && $video->error_message)
                                <div class="mt-1 text-xs text-red-600">
                                    {{ $video->error_message }}
                                </div>
                            @endif
                            
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
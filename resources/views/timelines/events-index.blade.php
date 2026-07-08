{{-- resources/views/timelines/events-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Manage Timeline Events"
            subtitle="Upload, organize, and manage events in this timeline."
            :actions="[
                'back' => [
                    'label' => 'Back to Timelines',
                    'href' => route('admin.timelines.index'),
                    'variant' => 'ghost',
                ],
                'uploadImage' => [
                    'label' => 'Create New Event',
                    'href' => route('admin.timelines.events.create', $timeline),
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-sm">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Criminal Case</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Time</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>


            <tbody>

                @forelse ($events as $event)
                    
                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4">{{ $event->title }}</td>

                        <td class="px-6 py-4">{{ $event->timeline->name }}</td>

                        <td class="px-6 py-4">{{ $event->occurred_at?->format('d M Y') }}</td>

                        <td class="px-6 py-4">{{ $event->occurred_at?->format('H:i') }}</td>

                        <td class="px-6 py-4">{{ $event->type ? $event->type : '...' }}</td>

                        <td class="px-6 py-4">
                           <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.timelines.events.edit', [$timeline, $event]) }}"    
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.timelines.events.destroy', [$timeline, $event]) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')"
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
                            colspan="6"
                            class="px-6 py-12 text-center text-zinc-500"
                        >
                            No timeline events added.
                        </td>

                    </tr>

                @endforelse



            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6 flex justify-end">
            {{ $events->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>

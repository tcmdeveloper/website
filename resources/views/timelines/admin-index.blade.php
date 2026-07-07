{{-- resources/views/timelines/admin-index.blade.php --}}

<x-layouts.dashboard>


    <x-ui.card>

        <x-ui.header-actions
            :title="$title ?? 'Timelines'"
            :subtitle="$subtitle ?? 'Manage and organize your timelines.'"
            :actions="[
                'create' => [
                    'label' => 'Create New Timeline',
                    'href' => route('admin.timelines.create', request()->only('case')),
                    'variant' => 'primary',
                    'icon' => 'heroicon-o-clock'
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-sm">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4 w-[24rem]">Name</th>
                    <th class="px-6 py-4">Criminal Case</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Events</th>
                    <th class="px-6 py-4">Visibility</th>
                    <th class="px-6 py-4">Views</th>
                    <th class="px-6 py-4">Created</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($timelines as $timeline)
                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4">
                            {{ $timeline->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $timeline->criminalCase->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $timeline->type->label() }}
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ number_format($timeline->event_count) }}
                        </td>

                        <td class="px-6 py-4">
                            @if($timeline->isPublished())
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">
                                    Public
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    Private
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                           {{ $timeline->formatted_views }}
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $timeline->created_at->format('M j, Y') }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.timelines.edit', $timeline) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.timelines.events.index', $timeline) }}"
                                >
                                    Events
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.timelines.destroy', $timeline) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this timeline? This action cannot be undone.')"
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
                            colspan="7"
                            class="px-6 py-12 text-center text-zinc-500"
                        >
                            No timelines found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $timelines->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>
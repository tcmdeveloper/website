{{-- resources/views/documents/admin-index.blade.php --}}

{{-- ACTION BUTTONS --}}

@php
    $actions = [
        'create' => [
            'label' => 'Upload New Document',
            // 'icon' => 'heroicon-o-arrow-up-tray',
            'href' => route('admin.documents.create', request()->only('case')),
            'variant' => 'primary',
        ]
    ];

    // Add back button if results are filtered
    if (request()->filled('case')) {
        $actions = array_merge([
            'back' => [
                'label' => 'Back to Documents',
                'href' => route('admin.documents.index'),
                'variant' => 'ghost',
            ],
        ], $actions);
    }
@endphp

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Documents"
            subtitle="Manage the documents for this criminal case."
            :actions="$actions"
        />
        

        {{-- Alert --}}
        <x-ui.alert />


        {{-- Table --}}

        <table class="w-full border text-xs">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Pages</th>
                    <th class="px-6 py-4">Date Filed</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($documents as $document)

                    <tr class="border-b border-zinc-100 text-zinc-500">

                        <td class="px-6 py-4">
                            {{ $document->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $document->slug }}
                        </td>

                        <td class="px-6 py-4">
                           {{ number_format($document->document_pages_count) }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $document->created_at->format('M j, Y') }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.documents.edit', $document) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.documents.destroy', $document) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.')"
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
                            No documents found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $documents->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>
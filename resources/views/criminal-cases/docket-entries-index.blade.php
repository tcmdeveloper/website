{{-- resources/views/criminal-cases/docket-entries-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Manage Docket Entries"
            subtitle="Upload, organize, and manage the docket entriess listed in this criminal case."
            :actions="[
                'back' => [
                    'label' => 'Back to Criminal Cases',
                    'href' => route('admin.criminal-cases.index'),
                    'variant' => 'ghost',
                ],
                'uploadImage' => [
                    'label' => 'Upload New Image',
                    'href' => route('admin.criminal-cases.images.create', $criminalCase),
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-sm">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4">Sequence Number</th>
                    <th class="px-6 py-4">Docket Code</th>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4 text-center">Published Document</th>
                    <th class="px-6 py-4">Date Filed</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>


            <tbody>

                @forelse ($docketEntries as $docketEntry)
                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 flex gap-4 items-center">
                            {{-- <x-ui.image
                                :image="$image"
                                class="w-20"
                                sizes="80px"
                            /> --}}
                            {{ $docketEntry->sequence_number }}
                        </td>

                        <td class="px-6 py-4">{{ $docketEntry->docket_code }}</td>

                        <td class="px-6 py-4">{{ Str::limit($docketEntry->title, 50) }}</td>

                        <td class="px-6 py-4 text-center">
                            @if($docketEntry->has_document)
                                <x-ui.image
                                    :image="$docketEntry->document->coverPage->image_path"
                                    class="w-20"
                                    sizes="80px"
                                />
                            @else
                                <x-ui.pip 
                                    label="No Attachments"
                                    variant="secondary"
                                />
                            @endif
                        </td>

                        <td class="px-6 py-4">{{ $docketEntry->filed_at->format('M d, Y') }}</td>

                        <td class="px-6 py-4">
                           <div class="flex justify-end gap-2">
{{-- 
                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.criminal-cases.images.edit', [$criminalCase, $docketEntry]) }}"    
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.criminal-cases.images.destroy', [$criminalCase, $docketEntry]) }}"
                                    onsubmit="return confirm('Delete this image?')"
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
                                </form> --}}

                            </div>
                        </td>
                    </tr>
                
                    @empty

                    <tr>

                        <td
                            colspan="6"
                            class="px-6 py-12 text-center text-zinc-500"
                        >
                            No docket entries have been added.
                        </td>

                    </tr>

                @endforelse



            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6 flex justify-end">
            {{-- {{ $docketEntries->links() }} --}}
        </div>


    </x-ui.card>


</x-layouts.dashboard>

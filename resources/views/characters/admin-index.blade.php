{{-- resources/views/characters/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            :title="$title ?? 'Characters'"
            :subtitle="$subtitle ?? 'Manage and organize the characters from criminal cases.'"
            :actions="[
                'create' => [
                    'label' => 'Create New Character',
                    'href' => route('admin.characters.create'),
                    'variant' => 'primary',
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-xs">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4 w-[24rem]">Name</th>
                    <th class="px-6 py-4">Criminal Case</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Date of Birth</th>
                    <th class="px-6 py-4">Views</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($characters as $character)
                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 flex gap-4 items-center">

                            <x-ui.image
                                :image="$character->display_image"
                                class="w-20"
                                 sizes="(min-width: 640px) 80px, 100vw"
                                is_optimized="{{ $character->display_image->is_optimized ?? true }}"
                            />

                            <div class="max-w-md line-clamp-2 font-medium">
                                {{ $character->name }}
                            </div>

                        </td>

                        <td class="px-6 py-4">

                            @foreach($character->criminalCases as $criminalCase)
                                {{ $criminalCase->name}}
                            @endforeach

                        </td>

                        <td class="px-6 py-4">
                            {{ $character->type }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $character->date_of_birth?->format('M j, Y') }}
                        </td>

                        <td class="px-6 py-4">
                            {{ number_format($character->views) }}
                        </td>

                     
                        

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.characters.edit', $character) }}"
                                >
                                    Edit
                                </x-ui.button>

        
                                
                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.characters.images.index', $character) }}"
                                >
                                    Images
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.characters.destroy', $character) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this character? This action cannot be undone.')"
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
                            No characters have been added.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $characters->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>
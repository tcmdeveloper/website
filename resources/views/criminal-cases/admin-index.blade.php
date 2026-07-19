{{-- resources/views/criminal-cases/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            :title="$title ?? 'Criminal Cases'"
            :subtitle="$subtitle ?? 'Manage and organize your criminal cases.'"
            :actions="[
                'create' => [
                    'label' => 'Create New Case',
                    'href' => route('admin.criminal-cases.create', request()->only('case')),
                    'variant' => 'primary',
                ]
            ]"
        />

        <x-ui.alert />

        <table class="w-full border text-xs">

            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200 text-left text-zinc-600 font-medium">
                    <th class="px-6 py-4 w-[24rem]">Name</th>
                    <th class="px-6 py-4">Visibility</th>
                    <th class="px-6 py-4">Case number</th>
                    <th class="px-6 py-4">Arrest Date</th>
                    <th class="px-6 py-4 text-center">Articles</th>
                    <th class="px-6 py-4 text-center">Documents</th>
                    <th class="px-6 py-4 text-center">Views</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($criminalCases as $criminalCase)
                    <tr class="border-b border-zinc-100">

                        <td class="px-6 py-4 flex gap-4 items-center">

                            <x-ui.image
                                :image="$criminalCase->display_image"
                                class="w-20"
                                is_optimized="{{ $criminalCase->display_image->is_optimized ?? true }}"
                            />

                            <div class="max-w-md line-clamp-2">
                                {{ $criminalCase->name }}
                            </div>

                        </td>

                        <td class="px-6 py-4">

                            @if($criminalCase->isPublished())
                                <x-ui.pip
                                    label="Public"
                                    variant="success"
                                />
                            @else
                                <x-ui.pip
                                    label="Private"
                                    variant="ghost"
                                />
                            @endif

                        </td>

                        <td class="px-6 py-4">
                            {{ $criminalCase->criminal_case_number_display }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $criminalCase->arrest_date?->format('M j, Y') }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ number_format($criminalCase->articles_count) }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ number_format($criminalCase->documents_count) }}
                        </td>

                        <td class="px-6 py-4 text-center">
                           {{ number_format($criminalCase->formatted_views) }}
                        </td>

                        

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.criminal-cases.edit', $criminalCase) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.criminal-cases.docket-entries.index', $criminalCase) }}"
                                >
                                    Docket
                                </x-ui.button>
                                
                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.criminal-cases.images.index', $criminalCase) }}"
                                >
                                    Images
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.criminal-cases.destroy', $criminalCase) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this criminal case? This action cannot be undone.')"
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
                            No criminal cases found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $criminalCases->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>
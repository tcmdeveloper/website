{{-- resources/views/criminal-cases/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Criminal Cases"
            subtitle="Manage the criminal cases for this site."
            :href="route('admin.criminal-cases.create')"
            label="New criminal case"
        />


        {{-- Table --}}

        <table class="w-full border">

            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-sm text-zinc-600">

                    <th class="px-6 py-4 font-medium">
                        Name
                        
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Slug
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Articles
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Created
                    </th>

                    <th class="px-6 py-4 w-32"></th>

                </tr>
            </thead>

            <tbody>

                @forelse($criminalCases as $case)

                    <tr class="border-b border-zinc-100 text-xs">

                        <td class="px-6 py-4 text-zinc-500 font-medium">
                            <div class="flex items-stretch">
                                <span>
                                    {{ $case->name }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $case->slug }}
                        </td>

                        <td class="px-6 py-4">
                            0
                        </td>

                        <td class="px-6 py-4 text-zinc-500">
                            {{ $case->created_at->format('M j, Y') }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.criminal-cases.edit', $case) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.criminal-cases.destroy', $case) }}"
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
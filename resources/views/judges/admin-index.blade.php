{{-- resources/views/judges/admin-index.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Judges"
            subtitle="Manage the judges for the cases we cover."
            :actions="[
                'create' => [
                    'label' => 'Create New Judge',
                    'href' => route('admin.judges.create'),
                    'variant' => 'primary',
                ]
            ]"
        />


        {{-- Table --}}

        <table class="w-full border text-sm">

            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-sm text-zinc-600">
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">First Name</th>
                    <th class="px-6 py-4">Last Name</th>
                    <th class="px-6 py-4">Court</th>
                    <th class="px-6 py-4">Case(s)</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>

            <tbody>
                @forelse($judges as $judge)
                    <tr class="border-b border-zinc-100 text-zinc-500">

                        <td class="px-6 py-4">{{ $judge->title }}</td>
                        <td class="px-6 py-4">{{ $judge->first_name }}</td>
                        <td class="px-6 py-4">{{ $judge->last_name }}</td>
                        <td class="px-6 py-4">{{ $judge->court }}</td>
                        <td class="px-6 py-4">
                            <ul>
                                @foreach($judge->criminalCases as $case)
                                    <li>{{ $case->name}}</li>
                                @endforeach
                            </ul>
                        </td>
                        

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.judges.edit', $judge) }}"
                                >
                                    Edit
                                </x-ui.button>

                                <x-ui.button
                                    size="xs"
                                    variant="ghost"
                                    href="{{ route('admin.judges.images.index', $judge) }}"
                                >
                                    Images
                                </x-ui.button>

                                <form
                                    method="POST"
                                    action="{{ route('admin.judges.destroy', $judge) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this judge? This action cannot be undone.')"
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
                            No Judges found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        <div class="mt-6">
            {{ $judges->links() }}
        </div>


    </x-ui.card>


</x-layouts.dashboard>
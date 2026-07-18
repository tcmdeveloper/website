{{-- resources/views/criminal-cases/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Create a New Case File"
            subtitle="Enter the details for the new criminal case."
            :actions="[
                'back' => [
                    'label' => 'Back to Criminal Cases',
                    'href' => route('admin.criminal-cases.index'),
                    'variant' => 'ghost',
                ]
            ]"
        />


        <form 
            method="POST" 
            action="{{ route('admin.criminal-cases.store') }}" 
            class="space-y-5"
        >

            @csrf

            {{-- Criminal case name --}}

            <div>
                <x-ui.input
                    name="name"
                    type="text"
                    label="Defendant name"
                    placeholder="Defendant name"
                    :value="old('name')"
                />
            </div>


            {{-- Criminal case number --}}

            <div>
                <x-ui.input
                    name="criminal_case_number"
                    type="text"
                    label="Case number"
                    placeholder="Case number"
                    :value="old('criminal_case_number')"
                />
            </div>


            {{-- Arrest date --}}

            <div>
                <x-ui.input
                    type="date"
                    name="arrest_date"
                    label="Date of Arrest"
                    :value="old('arrest_date')"
                />
            </div>


            {{-- Description --}}

            <div>
                <x-ui.textarea
                    name="description"
                    label="Description"
                    rows="3"
                    placeholder="Description"
                    :value="old('description')"
                />
            </div>


            {{-- Visibility --}}

            <div>
                <x-ui.select
                    name="is_published"
                    label="Visibility"
                    :options="[
                        1 => 'Public',
                        0 => 'Private',
                    ]"
                    :value="old('is_published')"
                    placeholder="Set visibility"
                />
            </div>


            {{-- Submit form --}}

            <div class="flex justify-start gap-2">
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Create case
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.criminal-cases.index') }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>

    
    </x-ui.card>


</x-layouts.dashboard>
{{-- resources/views/criminal-cases/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Edit Criminal Case"
            subtitle="Update and manage your true crime article."
            :actions="[
                'back' => [
                    'label' => 'Back to Criminal Cases',
                    'href' => route('admin.criminal-cases.index'),
                    'icon' => 'heroicon-o-arrow-left',
                    'variant' => 'ghost',
                ]
            ]"
        />

        <x-ui.alert />

        <form 
            method="POST" 
            action="{{ route('admin.criminal-cases.update', $criminalCase) }}" 
            class="space-y-5"
        >

            @csrf
            @method('PATCH')

            {{-- Criminal case name --}}

            <div>
                <x-ui.input
                    name="name"
                    type="text"
                    label="Defendant name"
                    placeholder="Defendant name"
                    :value="old('name', $criminalCase->name)"
                />
            </div>


            {{-- Criminal case number --}}

            <div>
                <x-ui.input
                    name="criminal_case_number"
                    type="text"
                    label="Case number"
                    placeholder="Case number"
                    :value="old('criminal_case_number', $criminalCase->criminal_case_number)"
                />
            </div>


            {{-- Arrest date --}}

            <div>
                <x-ui.input
                    type="date"
                    name="arrest_date"
                    label="Date of Arrest"
                    :value="old('arrest_date', $criminalCase->arrest_date)"
                />
            </div>


            {{-- Description --}}

            <div>
                <x-ui.textarea
                    name="description"
                    label="Description"
                    rows="3"
                    placeholder="Description"
                    :value="old('description', $criminalCase->description)"
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
                    :value="old('is_published', (int) $criminalCase->is_published)"
                    placeholder="Set visibility"
                />
            </div>


            {{-- Submit form --}}

            <div class="flex justify-start gap-2">
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Save changes
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
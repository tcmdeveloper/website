{{-- resources/views/characters/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Create a New Character"
            subtitle="Enter the details for this new character."
            :actions="[
                'back' => [
                    'label' => 'Back to Characters',
                    'href' => route('admin.characters.index'),
                    'variant' => 'ghost',
                ]
            ]"
        />

        <x-ui.alert />

        <form 
            method="POST" 
            action="{{ route('admin.characters.store') }}" 
            class="space-y-5"
        >

            @csrf

            {{-- name --}}

            <div>
                <x-ui.input
                    name="name"
                    type="text"
                    label="Character name"
                    placeholder="Character name"
                    :value="old('name')"
                />
            </div>


            <div>
                <x-ui.select
                    name="criminal_case_id"
                    label="Criminal Case"
                    :options="$criminalCases->pluck('name', 'id')->toArray()"
                    :value="old('criminal_case_id')"
                    placeholder="Select a criminal case"
                />
            </div>
            

            {{-- Gender and Type --}}

            <div class="grid grid-cols-2 gap-5">

                <x-ui.select
                    name="gender"
                    label="Gender"
                    :options="[
                        'male' => 'Male',
                        'female' => 'Female',
                    ]"
                    :value="old('gender')"
                    placeholder="Select gender"
                />

                <x-ui.select
                    name="type"
                    label="Role"
                    :options="[
                        'defendant' => 'Defendant',
                        'victim' => 'Victim',
                        'witness' => 'Witness',
                        'lawyer' => 'Lawyer',
                        'judge' => 'Judge',
                        'investigator' => 'Investigator',
                        'family' => 'Family',
                        'other' => 'Other',
                    ]"
                    :value="old('type')"
                />

            </div>


            {{-- Date of birth and death --}}

            <div class="grid grid-cols-2 gap-5">

                <div>
                    <x-ui.input
                        name="date_of_birth"
                        label="Date of Birth"
                        type="date"
                        :value="old('date_of_birth')"
                    />
                </div>

                <div>
                    <x-ui.input
                        name="date_of_death"
                        label="Date of Death"
                        type="date"
                        :value="old('date_of_death')"
                    />
                </div>

            </div>


            {{-- Description --}}

            <div>
                <x-ui.textarea
                    name="description"
                    label="Description"
                    rows="5"
                    placeholder="Write a short description of this character..."
                >{{ old('description') }}</x-ui.textarea>
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
                    Create Character
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.characters.index') }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>

    
    </x-ui.card>


</x-layouts.dashboard>
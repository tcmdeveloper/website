{{-- resources/views/judges/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            :title="$title ?? 'Create New Judge'"
            :subtitle="$subtitle ?? 'Update details for this judge.'"
            :actions="[
                'back' => [
                    'label' => 'Back to Judges',
                    'href' => route('admin.judges.index'),
                    'variant' => 'ghost',
                ]
            ]"
        />

        <x-ui.alert />

        <form 
            method="POST" 
            action="{{ route('admin.judges.update', $judge) }}" 
            class="space-y-5"
        >

            @csrf
            @method('PATCH')


            {{-- Title --}}

            <div>
                <x-ui.input
                    name="title"
                    type="text"
                    label="Title"
                    placeholder="Title"
                    :value="old('title', $judge->title)"
                />
            </div>


            {{-- First name --}}

            <div>
                <x-ui.input
                    name="first_name"
                    type="text"
                    label="First name"
                    placeholder="First name"
                    :value="old('first_name', $judge->first_name)"
                />
            </div>


            {{-- Middle name --}}

            <div>
                <x-ui.input
                    name="middle_name"
                    type="text"
                    label="Middle name"
                    placeholder="Middle name"
                    :value="old('middle_name', $judge->middle_name)"
                />
            </div>


            {{-- Last name --}}

            <div>
                <x-ui.input
                    name="last_name"
                    type="text"
                    label="Last name"
                    placeholder="Last name"
                    :value="old('last_name', $judge->last_name)"
                />
            </div>


            {{-- Court --}}

            <div>
                <x-ui.input
                    name="court"
                    type="text"
                    label="Court"
                    placeholder="Court"
                    :value="old('court', $judge->court)"
                />
            </div>


            {{-- Submit form --}}

            <div>
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Save Changes
                </x-ui.button>
            </div>


        </form>


    </x-ui.card>


</x-layouts.dashboard>
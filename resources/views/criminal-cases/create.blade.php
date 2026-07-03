{{-- resources/views/criminal-cases/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Create a new criminal case"
            subtitle="Enter the details for the new criminal case."
            :href="route('admin.criminal-cases.create')"
            label="All categories"
            buttonVariant="ghost"
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
                    label="Case name"
                    placeholder="Case name"
                    :value="old('name')"
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
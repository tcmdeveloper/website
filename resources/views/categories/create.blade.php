{{-- resources/views/categories/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Create a new category"
            subtitle="Enter the details for the new category."
            :href="route('admin.categories.create')"
            label="All categories"
            buttonVariant="ghost"
        />


        <form 
            method="POST" 
            action="{{ route('admin.categories.store') }}" 
            class="space-y-5"
        >

            @csrf

            {{-- Category name --}}

            <div>
                <x-ui.input
                    name="name"
                    type="text"
                    label="Category name"
                    placeholder="Category name"
                    :value="old('name')"
                />
            </div>


            {{-- Category description --}}

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
                    Create category
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.categories.index') }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>

    
    </x-ui.card>


</x-layouts.dashboard>
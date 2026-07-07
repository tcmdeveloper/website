{{-- resources/views/criminal-cases/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Create New Timeline"
            subtitle="Enter the details for the new timeline."
            :actions="[
                'back' => [
                    'label' => 'Back to Timelines',
                    'href' => route('admin.timelines.index'),
                    'variant' => 'ghost',
                    'icon' => 'heroicon-o-arrow-left'
                ]
            ]"
        />


        <form 
            method="POST" 
            action="{{ route('admin.timelines.store') }}" 
            class="space-y-5"
        >

            @csrf


            {{-- Criminal case --}}

            <div>
                <x-ui.select
                    name="criminal_case_id"
                    label="Criminal Case"
                    :options="$criminalCases"
                    value="{{old('criminal_case_id', ($selectedCase ?? null)?->id)}}"
                    placeholder="Select a criminal case"
                />
            </div>


            {{-- Name & slug auto feed --}}

            <div
                x-data="{
                    name: @js(old('name')),
                    slug: @js(old('slug')),
                    slugEdited: false
                }"
                class="space-y-5"
            >


                {{-- Title --}}

                <div>
                    <x-ui.input
                        name="name"
                        label="Timeline Name"
                        x-model="name"
                        placeholder="Timeline name"
                        @input="
                            if (!slugEdited) {
                                slug = name
                                    .toLowerCase()
                                    .trim()
                                    .replace(/[^a-z0-9\s-]/g, '')
                                    .replace(/\s+/g, '-')
                                    .replace(/-+/g, '-')
                            }
                        "
                    />
                </div>


                {{-- Slug --}}

                <div>
                    <x-ui.input
                        name="slug"
                        label="Slug"
                        x-model="slug"
                        placeholder="URL identifier"
                        @input="slugEdited = true"
                    />
                </div>


            </div>

            
            {{-- Timeline Type Options --}}

            <div>
                <x-ui.select
                    name="type"
                    label="Timeline type"
                    placeholder="Select timeline type"
                    :options="collect(\App\Enums\TimelineType::cases())
                        ->mapWithKeys(fn ($type) => [$type->value => $type->label()])
                        ->all()"
                    value="{{old('type')}}"
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
                    label="Status"
                    :options="[
                        0 => 'Private',
                        1 => 'Public',
                    ]"
                    :value="old('is_published')"

                />
            </div>


            {{-- Submit form --}}

            <div class="flex justify-start gap-2">
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Save Timeline
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
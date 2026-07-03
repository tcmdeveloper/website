{{-- resources/views/documents/create.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-5xl">

        <x-ui.header-actions
            title="Create a new document"
            subtitle="Enter the details for the new document."
            :href="route('admin.documents.create')"
            label="All documents"
            buttonVariant="ghost"
        />


        <form 
            method="POST" 
            action="{{ route('admin.documents.store') }}" 
            class="space-y-5"
            enctype="multipart/form-data"
        >

            @csrf


            {{-- Criminal Case --}}

            <div>
                <x-ui.select
                    name="criminal_case_id"
                    label="Criminal case"
                    :options="$criminalCases"
                    value="{{old('criminal_case_id')}}"
                    placeholder="Select a criminal case"
                />
            </div>


            {{-- PDF document --}}

            <div x-data="{ filename: '' }">

                <label class="block text-sm font-medium text-zinc-700 mb-2">
                    PDF document
                </label>

                <label
                    for="pdf"
                    class="
                        flex flex-col items-center justify-center
                        w-full p-8
                        rounded-xl
                        border-2 border-dashed border-zinc-300
                        bg-zinc-50
                        cursor-pointer
                        transition
                        hover:border-blue-500
                        hover:bg-blue-50
                    "
                >
                    <x-ui.icon
                        name="document-arrow-up"
                        class="w-10 h-10 text-zinc-400 mb-3"
                    />

                    <template x-if="!filename">
                        <div class="text-center">
                            <p class="font-medium text-zinc-700">
                                Click to select a PDF
                            </p>
                            <p class="text-sm text-zinc-500 mt-1">
                                PDF only • Maximum 100 MB
                            </p>
                        </div>
                    </template>

                    <template x-if="filename">
                        <div class="text-center">
                            <p class="font-medium text-green-600">
                                ✓ File selected
                            </p>
                            <p
                                class="mt-2 text-sm text-zinc-600 break-all"
                                x-text="filename"
                            ></p>
                        </div>
                    </template>
                </label>

                <input
                    id="pdf"
                    type="file"
                    name="pdf"
                    accept="application/pdf"
                    class="hidden"
                    @change="filename = $event.target.files[0]?.name ?? ''"
                />

                @error('pdf')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>


            {{-- Document name --}}

            <div>
                <x-ui.input
                    name="name"
                    type="text"
                    label="Document name"
                    placeholder="Document name"
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


            {{-- Visibility --}}

            <div>
                <x-ui.select
                    name="is_published"
                    label="Status"
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
                    Create document
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.documents.index') }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>

    
    </x-ui.card>


</x-layouts.dashboard>
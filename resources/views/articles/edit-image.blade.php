{{-- resources/views/articles/create-image.blade.php --}}

<x-layouts.dashboard>


    <x-ui.card class="max-w-4xl">

        <x-ui.header-actions
            title="{{ $title }}"
            subtitle="{{ $subtitle }}"
            :actions="$actions"
        />

        <x-ui.alert />

        <form
            id="imageForm"
            method="POST"
            action="{{ route('admin.articles.images.update', [$article, $image]) }}"
            x-data="imageUploader('{{ $image?->image_url ?? '' }}')"
            @submit.prevent="submit"
            class="space-y-6"
        >
            @csrf
            @method('PATCH')

            <input
                x-ref="file"
                type="file"
                accept="image/*"
                class="hidden"
                @change="selectImage"
            >

            <input
                type="hidden"
                name="cropped_image"
                x-model="croppedImage"
            >

            
            
            <div
                class="relative flex aspect-video items-center justify-center overflow-hidden rounded border border-zinc-300 bg-zinc-50"
            >

                <img
                    x-show="preview"
                    :src="preview"
                    x-ref="preview"
                    class="max-h-full max-w-full object-contain"
                >

                <template x-if="!preview">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <x-ui.button
                            type="button"
                            @click="$refs.file.click()"
                            size="sm"
                            variant="primary"
                        >
                            Choose Image
                        </x-ui.button>
                    </div>
                </template>

                <div
                    x-show="preview"
                    class="absolute bottom-3 right-3"
                >
                    <x-ui.button
                        type="button"
                        @click="$refs.file.click()"
                        size="sm"
                        variant="ghost"
                    >
                        Replace Image
                    </x-ui.button>
                </div>

            </div>

            {{-- Caption --}}

            <div>
                <x-ui.input
                    name="caption"
                    type="text"
                    label="Caption"
                    :value="old('caption', $image->caption)"
                />
            </div>


            {{-- Alt text --}}

            <div>
                <x-ui.input
                    name="alt_text"
                    type="text"
                    label="Alt text"
                    :value="old('alt_text', $image->alt_text)"
                />
            </div>


            {{-- Source --}}

            <div>
                <x-ui.input
                    name="source"
                    type="text"
                    label="Image source"
                    :value="old('source', $image->source)"
                />
            </div>


            {{-- Source URL --}}

            <div>
                <x-ui.input
                    name="source_url"
                    type="text"
                    label="Source URL"
                    :value="old('source_url', $image->source_url)"
                />
            </div>


            <div class="flex gap-2">

                <x-ui.button 
                    type="submit"
                    size="sm"
                >
                    Update Image
                </x-ui.button>


            </div>

        </form>

    </x-ui.card>

</x-layouts.dashboard>
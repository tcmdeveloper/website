{{-- resources/views/articles/create-image.blade.php --}}



@push('scripts')
    @vite('resources/js/image-uploader.js')
@endpush

<x-layouts.dashboard>

    <x-ui.card class="max-w-4xl">

        <x-ui.header-actions
            title="Upload a new image"
            subtitle="Select the image you want to add to this article."
            :actions="[
                'back' => [
                    'label' => 'Back to Images',
                    'href' => route('admin.articles.images.index', [$article]),
                    'variant' => 'ghost',
                ],
            ]"
        />

        <x-ui.alert />

        <form
            id="imageForm"
            method="POST"
            action="{{ route('admin.articles.images.store', $article) }}"
            x-data="imageUploader()"
            @submit.prevent="submit"
            class="space-y-6"
        >
            @csrf

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
                class="relative flex h-96 items-center justify-center overflow-hidden rounded border border-zinc-300 bg-zinc-50"
            >

                <template x-if="!preview">
                    <x-ui.button
                        type="button"
                        @click="$refs.file.click()"
                        class="rounded bg-stone-900 px-4 py-2 text-white"
                        size="sm"
                        variant="secondary"
                    >
                        Choose image
                    </x-ui.button>
                </template>

                <img
                    x-show="preview"
                    :src="preview"
                    x-ref="preview"
                    class="max-h-full max-w-full object-contain"
                >

                <div
                    x-show="preview"
                    class="absolute bottom-3 right-3 flex items-center gap-2"
                >
                    
                    {{--  'Make Featured' button --}}
                    <template x-if="featured">
                        <x-ui.button
                            type="button"
                            @click="featured = false"
                            variant="primary"
                            size="xs"
                        >
                            <x-heroicon-o-star class="w-4 h-4" />
                            Featured
                        </x-ui.button>
                    </template>

                    <template x-if="!featured">
                        <x-ui.button
                            type="button"
                            @click="featured = true"
                            variant="ghost"
                            size="xs"
                        >
                            <x-heroicon-o-star class="w-4 h-4" />
                            Make Featured
                        </x-ui.button>
                    </template>

                    <input type="hidden" name="is_featured" :value="featured ? 1 : 0">


                    {{-- 'Replace Image' button --}}
                    <x-ui.button
                        type="button"
                        @click="$refs.file.click()"
                        size="xs"
                        variant="ghost"
                    >
                        <x-heroicon-o-photo class="w-4 h-4" />
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
                    :value="old('caption', $article->caption)"
                />
            </div>


            {{-- Alt text --}}

            <div>
                <x-ui.input
                    name="alt_text"
                    type="text"
                    label="Alt text"
                    :value="old('alt_text', $article->alt_text)"
                />
            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            
                {{-- Credit name --}}

                <div>
                    <x-ui.input
                        name="credit_name"
                        type="text"
                        label="Credit source"
                        :value="old('credit_name', $article->credit_name)"
                    />
                </div>


                {{-- Credit URL --}}

                <div>
                    <x-ui.input
                        name="credit_url"
                        type="text"
                        label="Credit URL"
                        :value="old('credit_url', $article->credit_url)"
                    />
                </div>
            
            </div>


            <div class="flex gap-2">

                <x-ui.button 
                    type="submit"
                    size="sm"
                >
                    Upload image
                </x-ui.button>

                <x-ui.button 
                    type="button" 
                    @click="$refs.file.click()"
                    size="sm"
                    variant="secondary"
                >
                    Choose another
                </x-ui.button>

            </div>

        </form>

    </x-ui.card>

</x-layouts.dashboard>
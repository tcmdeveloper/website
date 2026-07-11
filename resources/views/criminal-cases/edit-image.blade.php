{{-- resources/views/criminal-cases/edit-image.blade.php --}}

@push('scripts')
    @vite('resources/js/image-uploader.js')
@endpush

<form
    id="optimizeForm"
    method="POST"
    action="{{ route('admin.criminal-cases.images.optimize', [$criminalCase, $image]) }}"
>
    @csrf
</form>


<x-layouts.dashboard>

    <x-ui.card class="max-w-4xl">

        <x-ui.header-actions
            title="{{ $title }}"
            subtitle="{{ $subtitle }}"
            :actions="[
                'back' => [
                    'label' => 'Back to Images',
                    'href' => route('admin.criminal-cases.images.index', [$criminalCase]),
                    'variant' => 'ghost',
                ],
            ]"
        />

        <x-ui.alert />

        <form
            id="imageForm"
            method="POST"
            action="{{ route('admin.criminal-cases.images.update', [$criminalCase, $image]) }}"
            x-data="imageUploader(
                '{{ $image->image_url ?? '' }}',
                @js($image->is_featured ?? false)
            )"
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
                class="relative flex h-96 items-center justify-center overflow-hidden rounded border border-zinc-300 bg-zinc-50"
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
                    class="absolute bottom-3 right-3 flex items-center gap-2"
                >


                    {{--  Optimize button --}}
                    @unless($image->has_multiformat)
                        <x-ui.button
                            type="submit"
                            form="optimizeForm"
                            variant="success"
                            size="xs"
                        ><x-heroicon-s-star class="w-4 h-4" />
                            Optimize
                        </x-ui.button>
                    @endunless
                    
                    
                    {{--  'Make Featured' button --}}
                    <template x-if="featured">
                        <x-ui.button
                            type="button"
                            @click="featured = false"
                            variant="primary"
                            size="xs"
                        >
                            <x-heroicon-o-check-circle class="w-4 h-4" />
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


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            
                {{-- Credit name --}}

                <div>
                    <x-ui.input
                        name="credit_name"
                        type="text"
                        label="Credit source"
                        :value="old('credit_name', $image->credit_name)"
                    />
                </div>


                {{-- Credit URL --}}

                <div>
                    <x-ui.input
                        name="credit_url"
                        type="text"
                        label="Credit URL"
                        :value="old('credit_url', $image->credit_url)"
                    />
                </div>

            </div>


            <div class="flex gap-2">

                <x-ui.button 
                    type="submit"
                    size="sm"
                >
                    Update Image
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.criminal-cases.images.index', [$criminalCase]) }}"
                >
                    Cancel
                </x-ui.button>


            </div>

        </form>

    </x-ui.card>

</x-layouts.dashboard>
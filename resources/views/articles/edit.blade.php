{{-- resources/views/admin/articles/edit.blade.php --}}

@push('scripts')

     @include('articles.partials.upload-image-script')

     <script>

        document.addEventListener('DOMContentLoaded', function () {

            const imageInput = document.getElementById('imageInput');
            const currentImage = document.getElementById('currentImage');
            

            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    currentImage.classList.add('hidden');
                });

            }

        });

    </script>

@endpush



<x-layouts.app
    title="Edit Article"
    subtitle="Update your article details."
>

    <x-ui.card class="mx-auto max-w-5xl">


        <form 
            method="POST" 
            action="{{ route('admin.articles.update', $article) }}" 
            class="space-y-5"
        >

            @csrf
            @method('PATCH')


            {{-- Title & slug auto feed --}}

            <div
                x-data="{
                    title: @js(old('title', $article->title)),
                    slug: @js(old('slug', $article->slug)),
                    slugEdited: false
                }"
                class="space-y-5"
            >


                {{-- Title --}}

                <div>
                    <x-ui.input
                        name="title"
                        label="Title"
                        x-model="title"
                        @input="
                            if (!slugEdited) {
                                slug = title
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
                        @input="slugEdited = true"
                    />
                </div>


            </div>


            {{-- Excerpt --}}

            <div>
                <x-ui.textarea
                    name="excerpt"
                    label="Excerpt"
                    rows="3"
                    value="{{$article->excerpt}}"
                    placeholder="Write something..."
                />
            </div>


            {{-- Content --}}

            <div x-data="markdownEditor"
                x-init="init()"
                class="grid grid-cols-2 gap-6">

                <!-- Editor -->

                <div class="form-group h-full flex flex-col">

                    <label
                        for="content"
                        class="block mb-1 text-sm text-stone-500 font-medium"
                    >
                        Content
                    </label>

                    <textarea
                        x-ref="content"
                        name="content"
                        class="flex-1 w-full border border-zinc-300 bg-yellow-50 rounded-sm p-4 resize-none shadow-sm"
                        placeholder="Write Markdown..."
                    >{{ old('content', $article->content) }}</textarea>

                </div>

                <!-- Preview -->
                <div class="form-group h-full flex flex-col">
                    <label
                        for="content"
                        class="block mb-1 text-sm text-stone-500 font-medium"
                    >
                        Preview
                    </label>
                    <div class="flex-1 border border-zinc-300 rounded-sm p-4 bg-white prose max-w-none overflow-auto shadow-sm">
                        <div x-html="previewMarkdown" class="markdown"></div>
                    </div>
                </div>

            </div>


            {{-- Featured image --}}

            <div class="bg-gray-100 border border-zinc-300 rounded-sm shadow-sm p-4 py-10 flex justify-center items-center w-full mx-auto mb-8">


                {{-- Current image canvase --}}

                <div 
                    id="currentImageCanvas" 
                    class="flex flex-col items-center"
                >

                    {{-- Featured image --}}

                    <div class="relative group">

                        <img
                            src="{{ url($article->featured_image->path) }}"
                            class="w-2xl"
                            alt="{{ $article->featured_image->alt_text }}"
                        >

                        
                        {{-- Edit image --}}

                        <button
                            type="button"
                            @click="$refs.featuredImageInput.click()"
                            class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100 cursor-pointer"
                            aria-label="Change image"
                        >

                            <x-heroicon-o-pencil class="h-8 w-8 text-white" />

                        </button>


                        {{-- Hidden image input --}}

                        <input
                            x-ref="featuredImageInput"
                            name="featured_image"
                            type="file"
                            accept="image/*"
                            class="hidden"
                        />


                        {{-- Hidden featured image id --}}

                        <input
                            type="hidden"
                            name="featured_image_id"
                            value="{{ $article->featuredImage?->id }}"
                        >
                    
                    </div>


                    {{-- Featured image meta  --}}

                    <div class="mt-6 w-full flex flex-col space-y-5">

                        {{-- Caption --}}

                        <div>
                            <x-ui.input
                                name="featured_image_caption"
                                type="text"
                                size="sm"
                                label="Image caption"
                                :value="old('featured_image_caption', $article->featured_image->caption)"
                            />
                        </div>


                        {{-- Alt text --}}

                        <div>
                            <x-ui.input
                                name="featured_image_alt_text"
                                type="text"
                                size="sm"
                                label="Meta title"
                                :value="old('featured_image_alt_text', $article->featured_image->alt_text)"
                            />
                        </div>


                        {{-- Source --}}

                        <div>
                            <x-ui.input
                                name="featured_image_source"
                                type="text"
                                size="sm"
                                label="Image source"
                                :value="old('featured_image_source', $article->featured_image->source)"
                            />
                        </div>


                        {{-- Source URL --}}

                        <div>
                            <x-ui.input
                                name="featured_image_source_url"
                                type="text"
                                size="sm"
                                label="Link to source"
                                :value="old('featured_image_source_url', $article->featured_image->source_url)"
                            />
                        </div>

                    </div>

                </div>
                {{-- End of featured image canvas --}}


                {{-- Image cropper canvase --}}

                <div id="imageCropperCanvas" class="mb-6">
                    <img
                        id="preview"
                        class="max-w-xl rounded-lg"
                    >
                    <input type="hidden" name="cropped_image" id="croppedImage">
                    <x-ui.button variant="secondary" class="hidden!">Cancel</x-ui.button>
                </div>                

            </div>


            {{-- Category --}}

            <div>
                <x-ui.select
                    name="category_id"
                    label="Category"
                    :options="$categories"
                    value="{{old('category_id', $article->category_id)}}"
                    placeholder="Select a category"
                />
            </div>


            {{-- Meta title --}}

            <div>
                <x-ui.input
                    name="meta_title"
                    type="text"
                    label="Meta title"
                    :value="old('meta_title', $article->meta_title)"
                />
            </div>


            {{-- Meta description --}}

            <div>
                <x-ui.textarea
                    name="meta_description"
                    label="Meta description"
                    rows="3"
                    :value="old('meta_description', $article->meta_description)"
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
                    :value="old('is_published', (int) $article->is_published)"
                    placeholder="Set visibility"
                />
            </div>


            {{-- Submit form --}}

            <div>
                <x-ui.button
                    type="submit"
                    size="lg"
                >
                    Update Article
                </x-ui.button>
            </div>


        </form>


    </x-ui.card>


</x-layouts.app>
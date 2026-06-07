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

    <x-ui.card class="mx-auto">


        <form method="POST" action="{{ route('articles.update', $article) }}" class="space-y-4">

            @csrf
            @method('PATCH')


            {{-- Featured image --}}

            <div class="flex flex-col items-center">

                <h1 class="text-3xl font-bold text-center mb-8">Featured image</h1>


                <div id="currentImage">
                    {{-- FEATURED IMAGE --}}
                    @if(!empty($article->featured_image))
            
                        <img
                            
                            src="{{ url($article->featured_image) }}"
                            class="m-0 w-full rounded-xs object-contain shadow-sm max-w-xl mx-auto mb-8"
                            alt="{{ $article->title }}"
                        >
                    @endif
                
                    <button
                        id="changeImageBtn"
                        type="button"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            border
                            rounded
                            shadow-sm
                            font-medium
                            cursor-pointer
                            transition-all
                            duration-300
                            hover:opacity-90
                            hover:no-underline
                            hover:translate-y-[1px] 
                            focus:outline-none
                            focus:ring-2
                            focus:ring-offset-2
                            active:scale-[0.98]
                            mt-1
                            gap-3
                            bg-gradient-to-r
                            from-blue-600
                            to-sky-500
                            border-blue-500
                            text-white
                            focus:ring-blue-600
                            px-6
                            py-1.5
                            text-lg"
                        onclick="document.getElementById('imageInput').click()"
                    >Change image</button>
                </div>


                <input
                    id="imageInput"
                    name="featured_image"
                    type="file"
                    accept="image/*"
                    class="hidden"
                />

                <div class="mb-6">
                    <img
                        id="preview"
                        class="max-w-full hidden rounded-lg"
                    >
                </div>

                <input type="hidden" name="cropped_image" id="croppedImage">

           
            </div>


            {{-- Form divider --}}

            <x-ui.divider class="mb-10" />


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


            {{-- Category --}}

            <x-ui.select
                name="category_id"
                label="Category"
                :options="$categories"
                value="{{old('category_id', $article->category_id)}}"
                placeholder="Select a category"
            />


            {{-- Meta title --}}

            <x-ui.input
                name="meta_title"
                type="text"
                label="Meta title"
                :value="old('meta_title', $article->meta_title)"
            />


            {{-- Meta description --}}

            <x-ui.textarea
                name="meta_description"
                label="Meta description"
                rows="3"
                value="{{$article->meta_description}}"
            />


            {{-- Visibility --}}

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


            {{-- Submit form --}}

            <x-ui.button
                type="submit"
                size="lg"
            >
                Update Article
            </x-ui.button>


        </form>


    </x-ui.card>


</x-layouts.app>
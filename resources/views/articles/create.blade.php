{{-- resources/views/admin/articles/create.blade.php --}}

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    {{-- YOUR SCRIPT MUST COME AFTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let cropper = null;

            const imageInput = document.getElementById('imageInput');
            const preview = document.getElementById('preview');
            const form = document.getElementById('articleForm');
            const croppedInput = document.getElementById('croppedImage');

            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();

                    reader.onload = function (event) {
                        preview.src = event.target.result;
                        preview.classList.remove('hidden');

                        if (cropper) cropper.destroy();

                        cropper = new Cropper(preview, {
                            aspectRatio: 16 / 9,
                            viewMode: 1,
                            dragMode: 'move',
                        });
                    };

                    reader.readAsDataURL(file);
                });
            }

            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (!cropper) return form.submit();

                    const canvas = cropper.getCroppedCanvas({
                        width: 1200,
                        height: 675
                    });

                    canvas.toBlob((blob) => {
                        const reader = new FileReader();

                        reader.onload = function () {
                            croppedInput.value = reader.result;
                            form.submit();
                        };

                        reader.readAsDataURL(blob);
                    }, 'image/jpeg', 0.9);
                });
            }
        });
    </script>
@endpush

<x-layouts.app 
    title="Create a new article"
    subtitle="Publish the content for your article in the form below."
>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <x-ui.card class="mx-auto">

        <form id="articleForm" method="POST" action="{{ route('articles.store') }}" class="space-y-4">

            <div 
                x-data="{
                    title: '',
                    slug: '',
                    slugEdited: false
                }"
                class="space-y-4"
            >

                {{-- Title --}}
                <div>
                    <x-ui.input
                        name="title"
                        type="text"
                        label="Title"
                        x-model="title"
                        @input="
                            slug = title
                                .toLowerCase()
                                .trim()
                                .replace(/[^a-z0-9\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/-+/g, '-')
                        "
                    />
                </div>

                {{-- Slug --}}
                <div>
                    <x-ui.input
                        name="slug"
                        type="text"
                        label="Slug"
                        x-model="slug"
                    />
                </div>

            </div>
                
            <div>

                {{-- Excerpt --}}
                <x-ui.textarea
                    name="excerpt"
                    label="Excerpt"
                    rows="3"
                    placeholder="Write something..."
                />
            </div>





            <div x-data="markdownEditor"
     x-init="init()"
     class="grid grid-cols-2 gap-6">

    <!-- Editor -->
    <div class="h-full flex flex-col">
        <textarea
            x-ref="content"
            name="content"
            class="flex-1 w-full border rounded p-2 resize-none"
            placeholder="Write Markdown..."
        >{{ old('content') }}</textarea>
    </div>

    <!-- Preview -->
    <div class="h-full flex flex-col">
        <div class="flex-1 border rounded p-4 bg-white prose max-w-none overflow-auto">
            <div x-html="previewMarkdown" class="markdown"></div>
        </div>
    </div>

</div>





            {{-- Category --}}
            <x-ui.select
                name="category_id"
                label="Category"
                :options="$categories"
                placeholder="Select a category"
            />

            {{-- Featured images --}}
                
            <input
                id="imageInput"
                name="featured_image"
                label="Featured image"
                type="file"
                accept="image/*"
            />

            <div class="mb-6">
                <img
                    id="preview"
                    class="max-w-full hidden rounded-lg"
                >
            </div>

            <input type="hidden" name="cropped_image" id="croppedImage">

            {{-- Meta title --}}
            <div>
                <x-ui.input
                    name="meta_title"
                    type="text"
                    label="Meta title"
                />
            </div>

            {{-- Meta description --}}
            <x-ui.textarea
                name="meta_description"
                label="Meta description"
                rows="3"
                placeholder="Write something..."
            />

            {{-- Visibility --}}
            <x-ui.select
                name="is_published"
                label="Status"
                :options="[
                    true => 'Public',
                    false => 'Private',
                ]"
                placeholder="Set visibility"
            />

            {{-- Submit --}}
            <x-ui.button
                type="submit"
                size="lg"
            >
                Create article
            </x-ui.button>
        
        </form>


    </x-ui.card>

</x-layouts.app>
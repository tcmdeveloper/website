{{-- resources/views/admin/articles/create.blade.php --}}

@push('styles')
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css"
    />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cropper;

            document.getElementById('imageInput').addEventListener('change', function (e) {

                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();

                reader.onload = function (event) {

                    const img = document.getElementById('preview');

                    img.src = event.target.result;
                    img.classList.remove('hidden');

                    if (cropper) {
                        cropper.destroy();
                    }

                    cropper = new Cropper(img, {
                        aspectRatio: 16 / 9,
                        viewMode: 1,
                    });
                };

                reader.readAsDataURL(file);
            });

            document.getElementById('articleForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;

    if (!cropper) {
        form.submit();
        return;
    }

    const canvas = cropper.getCroppedCanvas({
        width: 1200,
        height: 675
    });

    canvas.toBlob(function (blob) {

        const reader = new FileReader();

        reader.onload = function () {

            document.getElementById('croppedImage').value = reader.result;

            // IMPORTANT: ensure DOM is updated before submit
            setTimeout(() => {
                form.submit();
            }, 0);
        };

        reader.readAsDataURL(blob);

    }, "image/jpeg", 0.9);
}); console.log(document.getElementById('croppedImage').value);
        });
</script>
@endpush


<x-layouts.app 
    title="Articles"
    subtitle="Here are the articles you've been working on."
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

        <form id="articleForm" method="POST" action="{{ route('admin.articles.store') }}" class="space-y-4">

            @csrf

            {{-- Title --}}
            <div>
                <x-ui.input
                    name="title"
                    type="text"
                    label="Title"
                />
            </div>

            {{-- Slug --}}
            <div>
                <x-ui.input
                    name="slug"
                    type="text"
                    label="Slug"
                />
            </div>

            {{-- Excerpt --}}
            <x-ui.textarea
                name="excerpt"
                label="Excerpt"
                rows="3"
                placeholder="Write something..."
            />

            {{-- Content --}}
            <x-ui.textarea
                name="content"
                label="Content"
                rows="8"
                placeholder="Write something..."
            />

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
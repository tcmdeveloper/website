{{-- resources/views/admin/articles/edit.blade.php --}}

<x-layouts.app
    title="Edit Article"
    subtitle="Update your article details."
>

    <x-ui.card class="mx-auto">

        <form method="POST" action="{{ route('admin.articles.update', $article) }}" class="space-y-4">

            @csrf
            @method('PUT')

            {{-- Title --}}
            <x-ui.input
                name="title"
                type="text"
                label="Title"
                :value="old('title', $article->title)"
            />

            {{-- Slug --}}
            <x-ui.input
                name="slug"
                type="text"
                label="Slug"
                :value="old('slug', $article->slug)"
            />

            {{-- Excerpt --}}
            <x-ui.textarea
                name="excerpt"
                label="Excerpt"
                rows="3"
            >{{ old('excerpt', $article->excerpt) }}</x-ui.textarea>

            {{-- Content --}}
            <x-ui.textarea
                name="content"
                label="Content"
                rows="8"
            >{{ old('content', $article->content) }}</x-ui.textarea>

            {{-- Category --}}
            <x-ui.select
                name="category_id"
                label="Category"
                :options="$categories"
                :selected="old('category_id', $article->category_id)"
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
            >{{ old('meta_description', $article->meta_description) }}</x-ui.textarea>

            {{-- Visibility --}}
            <x-ui.select
                name="is_published"
                label="Status"
                :options="[
                    1 => 'Public',
                    0 => 'Private',
                ]"
                :selected="old('is_published', (int) $article->is_published)"
                placeholder="Set visibility"
            />

            <x-ui.button
                type="submit"
                size="lg"
            >
                Update Article
            </x-ui.button>

        </form>

    </x-ui.card>

</x-layouts.app>
{{-- resources/views/articles/edit.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            title="Edit Article"
            subtitle="Update and manage your true crime article."
            :actions="[
                'back' => [
                    'label' => 'Back to Articles',
                    'href' => route('admin.articles.index'),
                    'variant' => 'ghost',
                ]
            ]"
        />

        <x-ui.alert />

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
                    value="{!!html_entity_decode($article->excerpt)!!}"
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
                        <div x-html="previewMarkdown" class="prose-content"></div>
                    </div>
                </div>

            </div>


            {{-- Criminal case --}}

            <div>
                <x-ui.select
                    name="criminal_case_id"
                    label="Criminal case"
                    :options="$criminalCases"
                    value="{{old('criminal_case_id', $article->criminal_case_id)}}"
                    placeholder="Select a criminal case"
                />
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

            <div class="flex justify-start gap-2">
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Update article
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.articles.index') }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>

    
    </x-ui.card>


</x-layouts.dashboard>

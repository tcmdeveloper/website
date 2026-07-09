{{-- resources/views/articles/create.blade.php --}}

@push('scripts')
    @vite('resources/js/editor.js')
@endpush

<x-layouts.dashboard>

    <x-ui.card>

        <x-ui.header-actions
            :title="$title ?? 'Create New Article'"
            :subtitle="$subtitle ?? 'Enter the details for the new article.'"
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
            action="{{ route('admin.articles.store') }}" 
            class="space-y-5"
        >

            @csrf

            {{-- Title & slug auto feed --}}

            <div
                x-data="{
                    title: @js(old('title')),
                    slug: @js(old('slug')),
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
                        placeholder="Article title"
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
                        placeholder="URL identifier"
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
                    >{{ old('content') }}</textarea>

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
                    value="{{old('criminal_case_id', $selectedCase?->id)}}"
                    placeholder="Select a criminal case"
                />
            </div>


            {{-- Category --}}

            <div>
                <x-ui.select
                    name="category_id"
                    label="Category"
                    :options="$categories"
                    value="{{old('criminal_case_id', $selectedCategory?->id)}}"
                    placeholder="Select a category"
                />
            </div>


            {{-- Meta title --}}

            <div>
                <x-ui.input
                    name="meta_title"
                    type="text"
                    label="Meta title"
                    placeholder="Meta title"
                    :value="old('meta_title')"
                />
            </div>


            {{-- Meta description --}}

            <div>
                <x-ui.textarea
                    name="meta_description"
                    label="Meta description"
                    rows="3"
                    placeholder="Meta description"
                    :value="old('meta_description')"
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

            <div>
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Create article
                </x-ui.button>
            </div>


        </form>


    </x-ui.card>


</x-layouts.dashboard>
{{-- resources/views/categories/show.blade.php --}}

<x-layouts.app
    :title="$category->name"
    :subtitle="$category->description"
>

    <x-ui.container>

        <section aria-labelledby="latest-articles-heading">

            <h2 id="latest-articles-heading" class="sr-only">
                Latest articles
            </h2>

            <x-articles.grid :articles="$articles" />

        </section>

    </x-ui.container>
    
</x-layouts.app>
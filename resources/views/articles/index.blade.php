{{-- resources/views/admin/articles/index.blade.php --}}

<x-layouts.app 
    title="True Crime Articles"
    subtitle="Explore in-depth articles covering criminal cases, court documents, timelines, legal analysis, and the latest developments featured on True Crime Metrix."
>

    <x-ui.container class="max-w-5xl">

        <section aria-labelledby="latest-articles-heading">

            <h2 id="latest-articles-heading" class="sr-only">
                Latest articles
            </h2>

            <x-articles.grid :articles="$articles" />

        </section>

    </x-ui.container>

</x-layouts.app>
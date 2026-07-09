{{-- resources/views/admin/articles/index.blade.php --}}

<x-layouts.app 
    title="True Crime Articles"
    subtitle="In-depth articles about the cases we cover on True Crime Metrix."
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
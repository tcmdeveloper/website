{{-- resources/views/admin/articles/index.blade.php --}}

<x-layouts.app 
    title="Metrix blog articles"
    subtitle="Exploring ideas, analysis, and insights from the cases covered on True Crime Metrix."
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
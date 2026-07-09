{{-- resources/views/pages/home.blade.php --}}

<x-layouts.app 
    title="Explore True Crime Cases in Detail" 
    subtitle="Discover comprehensive timelines, people profiles, locations, evidence, and case analysis. Browse well-organized information on notorious and lesser-known true crime investigations in one searchable archive."
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
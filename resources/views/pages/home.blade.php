{{-- resources/views/pages/home.blade.php --}}

<x-layouts.app 
    title="True Crime Cases, Live Trials &<br>Evidence-Based Snark" 
    subtitle="Live trial coverage, court documents, and structured case breakdowns<br>from True Crime Metrix."
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
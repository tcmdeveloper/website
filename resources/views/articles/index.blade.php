{{-- resources/views/admin/articles/index.blade.php --}}

<x-layouts.app 
    title="Metrix blog articles"
    subtitle="Exploring ideas, analysis, and insights from the cases covered on True Crime Metrix."
>

    <x-articles.grid :articles="$articles" />

</x-layouts.app>
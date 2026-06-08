{{-- resources/views/categories/show.blade.php --}}

<x-layouts.app
    :title="$category->name"
    :subtitle="$category->description"
>

    <x-articles.grid :articles="$articles" />
    
</x-layouts.app>
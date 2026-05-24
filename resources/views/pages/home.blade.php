{{-- resources/views/pages/home.blade.php --}}

@props([
    'title' => null,
    'subtitle' => null,
])

<x-layouts.app 
    :title="$title" 
    :subtitle="$subtitle"
>

    <div class="text-center py-20">


        {{-- Extra large buttons --}}

        <div class="mt-8 flex justify-center gap-4">
            <a href="/about"
               class="btn btn-primary">
                Primary
            </a>

            <a href="/about"
               class="btn btn-secondary">
                Secondary
            </a>
        </div>

        

    </div>

</x-layouts.app>
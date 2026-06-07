{{-- resources/views/categories/show.blade.php --}}

<x-layouts.app
    :title="$category->name"
    :subtitle="$category->description"
>

    {{-- Articles --}}
    <section class="max-w-5xl mx-auto px-4 pb-16">

        <div class="grid grid-cols-2 gap-8">

            @forelse($articles as $article)

                <x-cards.article
                    :title="$article->title"
                    :excerpt="$article->excerpt"
                    :featuredImageUrl="$article->featured_image_url"
                    :authorName="$article->author->display_name"
                    :publishedAt="$article->published_at"
                    url="/articles/{{$article->slug}}"
                />

            @empty

                <div class="py-20 text-center">

                    <h2 class="text-xl font-medium">
                        No articles yet
                    </h2>

                    <p class="mt-2 text-zinc-500">
                        Check back soon for new content.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if($articles->hasPages())
            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @endif

    </section>

</x-layouts.app>
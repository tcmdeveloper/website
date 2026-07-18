{{-- resources/views/components/articles/grid.blade.php --}}

<div class="grid grid-cols-1 sm:grid-cols-2 gap-8">

    @forelse($articles as $article)

        
            <x-cards.article
                :title="$article->title"
                :excerpt="$article->excerpt"
                :featuredImage="$article->displayImage"
                :authorName="$article->author->display_name"
                :views="$article->views"
                :publishedAt="$article->published_at"
                url="/articles/{{$article->slug}}"
            />
   

    @empty

        <div class="py-20 text-center col-span-1 sm:col-span-2">

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

{{-- @if($articles->hasPages())
    <div class="mt-12">
        {{ $articles->links() }}
    </div>
@endif --}}


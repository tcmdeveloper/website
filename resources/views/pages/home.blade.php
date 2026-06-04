{{-- resources/views/pages/home.blade.php --}}

<x-layouts.app 
    title="True Crime Cases, Live Trials &<br>Evidence-Based Snark" 
    subtitle="Live trial coverage, court documents, and structured case breakdowns<br>from True Crime Metrix."
>

    <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2">

        @foreach ($articles as $article)

            <a href="{{ route('articles.show', $article->slug) }}"
               class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition">

                {{-- Image --}}
                <div class="aspect-video overflow-hidden bg-gray-100">
                    @if ($article->featured_image)
                        <img
                            src="{{ Storage::url($article->featured_image) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                            alt="{{ $article->title }}"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            No image
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5 space-y-3">

                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span>{{ $article->category->name ?? 'Uncategorized' }}</span>
                        <span>•</span>
                        <span>{{ $article->created_at->format('M d, Y') }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-2">
                        {{ $article->title }}
                    </h3>

                    <p class="text-sm text-gray-600 line-clamp-3">
                        {{ $article->excerpt }}
                    </p>

                    <div class="pt-2 text-sm font-medium text-blue-600">
                        Read more →
                    </div>

                </div>

            </a>

        @endforeach

    </div>
    

</x-layouts.app>
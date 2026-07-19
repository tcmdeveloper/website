{{-- resources/views/timeline-events/index.blade.php --}}

@push('scripts')
    <script>
        window.heatmapData = @json($heatmapData);
    </script>

    @vite('resources/js/timeline.js')
@endpush

{{-- @dd($heatmapData) --}}
<x-layouts.app
    title="{{ $timeline->criminalCase->name }} / {{ $timeline->name }} "
    subtitle="{{ Str::limit($timeline->description, 1000) }}"
    :meta="[
        'title' => $timeline->criminalCase->name. ' Case: ' . $timeline->name,

        'description' => Str::limit(
            strip_tags($timeline->description),
            200
        ),
    ]"
>

    <x-ui.container class="max-w-5xl">


        <div class="bg-white rounded-sm shadow p-6 mb-10">

            <div class="font-bold font-heading mb-3">
                Timeline Events
            </div>

            <div id="timeline-chart"></div>

        </div>


        


        {{-- Criminal Case Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            <div class="col-span-1 md:col-span-4">
                
                

                <div class="rounded-sm border border-zinc-200 bg-white p-6 shadow-sm">

                    <div class="font-bold font-heading mb-3">
                        The {{ $criminalCase->name }} Cases
                    </div>

                    <x-ui.image
                        :image="$criminalCase->display_image"
                        class="w-full sm:w-[267px] h-48 object-cover rounded-tl-sm rounded-tr-sm rounded-bl-none rounded-br-none"
                        sizes="(min-width: 640px) 267px, 100vw"
                    />

                </div>

            </div>

            <div class="md:col-span-8 flex flex-col gap-6">

                @forelse($timeline->events as $event)

                    <div class="group rounded-sm border border-zinc-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                        <div class="mb-5 flex items-center gap-3 text-xs">
                            <span class="rounded-full bg-red-metrix px-3 py-1 font-bold text-white">
                                {{ $event->occurred_at->format('Y') }}
                            </span>

                            <span class="text-slate-400">•</span>

                            <span class="font-medium text-slate-600">
                                {{ $event->date_label ? $event->date_label : $event->occurred_at->format('M j') }}
                            </span>
                        </div>

                        <h3 class="mb-4 text-xl font-semibold tracking-tight text-slate-900">
                            {{ $event->title }}
                        </h3>

                        <div class="mb-5 inline-flex items-center gap-1 rounded-lg bg-zinc-100 px-2 py-1.5 text-xs font-medium text-slate-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>

                            {{ $event->time_label ? $event->time_label : $event->occurred_at->format('H:i') }}
                        </div>

                        <p class="leading-6 text-slate-600 text-sm">
                            {{ $event->description }}
                        </p>

                        {{-- <div class="mt-6 border-t border-zinc-100 pt-5">
                            <a href="#"
                            class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 transition-colors hover:text-black">
                                View details

                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div> --}}

                    </div>

                @empty

                    <div class="col-span-full text-center py-16 text-zinc-500">
                        No timeline event found.
                    </div>

                @endforelse

            </div>

        </div>


    </x-ui.container>


</x-layouts.app>
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
                        Case / {{ $criminalCase->name }}
                    </div>

                    <x-ui.image
                        :image="$criminalCase->display_image"
                        class="w-full sm:w-[267px] h-48 object-cover rounded-tl-sm rounded-tr-sm rounded-bl-none rounded-br-none"
                        sizes="(min-width: 640px) 267px, 100vw"
                    />

                </div>

            </div>


            <div id="timeline-events" class="md:col-span-8 flex flex-col gap-6">

                @include('timeline-events.partials.events', [
                    'events' => $events,
                ])

            </div>


        </div>


    </x-ui.container>


</x-layouts.app>
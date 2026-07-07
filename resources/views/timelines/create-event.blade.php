{{-- resources/views/timelines/create-event.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-4xl">

        <x-ui.header-actions
            title="Create New Event"
            subtitle="Enter the details for the new event."
            :actions="[
                'back' => [
                    'label' => 'Back to Timelines',
                    'href' => route('admin.timelines.events.index', $timeline),
                    'variant' => 'ghost',
                    'icon' => 'heroicon-o-arrow-left'
                ],
            ]"
        />

        <x-ui.alert />

        <form 
            method="POST" 
            action="{{ route('admin.timelines.store') }}" 
            class="space-y-5"
        >

            @csrf


            {{-- Timelines --}}

            <div>
                <x-ui.select
                    name="timeline_id"
                    label="Timeline"
                    :options="$timelines"
                    value="{{old('timeline_id', ($timeline ?? null)?->id)}}"
                    placeholder="Select a timeline"
                />
            </div>


            {{-- Title --}}

            <div>
                <x-ui.input
                    name="title"
                    type="text"
                    label="Title"
                    placeholder="Title"
                    :value="old('title')"
                />
            </div>
            

            
            {{-- Type --}}

            <div>
                <x-ui.input
                    name="type"
                    type="text"
                    label="Type of Event"
                    placeholder="Event"
                    :value="old('event')"
                />
            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            
                {{-- Date --}}

                <div>
                    <x-ui.input
                        type="date"
                        name="occured_at_date"
                        label="Date of Event"
                        :value="old('occured_at_date')"
                    />
                </div>

                {{-- Date --}}

                <div>
                    <x-ui.input
                        type="time"
                        name="occured_at_time"
                        label="Time of Event"
                        :value="old('occured_at_time')"
                    />
                </div>

            </div>


            {{-- Description --}}

            <div>
                <x-ui.textarea
                    name="description"
                    label="Description"
                    rows="3"
                    placeholder="Description"
                    :value="old('description')"
                />
            </div>


            {{-- Visibility --}}

            {{-- <div>
                <x-ui.select
                    name="is_published"
                    label="Status"
                    :options="[
                        0 => 'Private',
                        1 => 'Public',
                    ]"
                    :value="old('is_published')"

                />
            </div> --}}


            {{-- Submit form --}}

            <div class="flex justify-start gap-2">
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Save Event
                </x-ui.button>

                <x-ui.button
                    type="button"
                    size="sm"
                    variant="secondary"
                    href="{{ route('admin.timelines.show', $timeline) }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>


    </x-ui.card>


</x-layouts.dashboard>
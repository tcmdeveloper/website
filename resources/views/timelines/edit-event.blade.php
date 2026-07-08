{{-- resources/views/timelines/create-event.blade.php --}}

<x-layouts.dashboard>

    <x-ui.card class="max-w-4xl">

        <x-ui.header-actions
            title="Edit Event"
            subtitle="Update and manage this event."
            :actions="[
                'back' => [
                    'label' => 'Back to Events',
                    'href' => route('admin.timelines.events.index', $timeline),
                    'variant' => 'ghost',
                    'icon' => 'heroicon-o-arrow-left'
                ],
            ]"
        />

        <x-ui.alert />

        <form 
            method="POST" 
            action="{{ route('admin.timelines.events.update', [$timeline, $event]) }}" 
            class="space-y-5"
        >

            @csrf
            @method('PATCH')


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
                    :value="old('title', $event->title)"
                />
            </div>
            

            
            {{-- Type --}}

            <div>
                <x-ui.input
                    name="type"
                    type="text"
                    label="Type of Event"
                    placeholder="Type of event"
                    :value="old('type', $event->type)"
                />
            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            
                {{-- Date --}}

                <div>
                    <x-ui.input
                        type="date"
                        name="occurred_at_date"
                        label="Date of Event"
                        :value="old('occurred_at_date', $event->occurred_at?->format('Y-m-d'))"
                    />
                </div>


                {{-- Time --}}

                <div>
                    <x-ui.input
                        type="time"
                        name="occurred_at_time"
                        label="Time of Event"
                        :value="old('occurred_at_time', $event->occurred_at?->format('H:i'))"
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
                    :value="old('description', $event->description)"
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
                    href="{{ route('admin.timelines.events.index', $timeline) }}"
                >
                    Cancel
                </x-ui.button>
            </div>


        </form>


    </x-ui.card>


</x-layouts.dashboard>
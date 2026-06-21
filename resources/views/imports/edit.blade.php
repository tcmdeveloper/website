<x-layouts.app
    title="Show Call Logs"
    subtitle="Listing the jail call logs for Donna Adelson."
>

    <x-ui.card class="mx-auto max-w-5xl">
        
        <form 
            method="POST" 
            action="{{ url('call-logs/patch') }}" 
            class="space-y-5"
        >

            @csrf
            @method('PATCH')

            <input type="hidden" name="id" value="{{$jailCallLog->id}}">


            {{-- Title & slug auto feed --}}

            <div
                {{-- x-data="{
                    title: @js(old('title', $article->title)),
                    slug: @js(old('slug', $article->slug)),
                    slugEdited: false
                }" --}}
                class="space-y-5"
            >


                @if(!empty($jailCallLog->raw_start_time))
                    {{-- Start time --}}

                    <div>
                        <x-ui.input
                            name="start_time"
                            type="text"
                            size="sm"
                            label="Start time"
                            :value="old('start_time', $jailCallLog->raw_start_time)"
                        />
                    </div>
                @endif


                @if(!empty($jailCallLog->raw_end_time))
                    {{-- Start time --}}

                    <div>
                        <x-ui.input
                            name="end_time"
                            type="text"
                            size="sm"
                            label="End time"
                            :value="old('end_time', $jailCallLog->raw_end_time)"
                        />
                    </div>
                @endif

            </div>


            {{-- Submit form --}}

            <div>
                <x-ui.button
                    type="submit"
                    size="sm"
                >
                    Save call log
                </x-ui.button>
            </div>


        
        </form>

    </x-ui.card>
</x-layouts.app>
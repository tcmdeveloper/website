{{-- resources/views/admin/dashboard.blade.php --}}

<x-layouts.app 
    title="Dashboard"
    subtitle="You can do admin things here for the site."
>
    <x-ui.card class="mx-auto">

        <div class="flex flex-wrap gap-3 mb-1">

            <x-ui.button href="#" variant="primary">
                Articles
            </x-ui.button>

            <x-ui.button href="{{route('transcriptions.index')}}" variant="primary">
                Transcriptions
            </x-ui.button>

        </div>
                    
                    {{-- <form
                        method="POST"
                        action="{{ route('admin.transcribe-youtube-video') }}"
                        class="space-y-5"
                        x-data="formHandler({
                            firstError: {{ Js::from($errors->keys()[0] ?? null) }}
                        })"
                        x-init="init()"
                    >

                        @csrf

                        <div>
                            <x-ui.input
                                name="url"
                                type="text"
                                label="YouTube video URL"
                                autofocus
                            />
                        </div>


                        <x-ui.button type="submit" variant="primary" size="sm">
                           Continue
                        </x-ui.button>


                    </form> --}}

    </x-ui.card>
</x-layouts.app>

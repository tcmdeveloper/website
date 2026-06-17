{{-- resources/views/admin/dashboard.blade.php --}}

<x-layouts.app 
    title="Dashboard"
    subtitle="You can do admin things here for the site."
>
    <x-ui.card class="mx-auto max-w-5xl">

        <div class="flex flex-wrap gap-3 mb-1">

            <x-ui.button href="{{route('profile.show')}}" variant="primary">
                Profile
            </x-ui.button>

            <x-ui.button href="{{route('admin.articles.index')}}" variant="primary">
                Articles
            </x-ui.button>

            <x-ui.button href="{{route('transcriptions.index')}}" variant="primary">
                Transcriptions
            </x-ui.button>

            <x-ui.button href="{{route('jail-call-logs.show')}}" variant="primary">
                Import Call Log
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

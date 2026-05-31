<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    

                    <form
                        method="POST"
                        action="{{ route('admin.transcribe-youtube-video') }}"
                        class="space-y-5"
                        x-data="formHandler({
                            firstError: {{ Js::from($errors->keys()[0] ?? null) }}
                        })"
                        x-init="init()"
                    >

                        @csrf

                        {{-- URL --}}
                        <div>
                            <x-ui.input
                                name="url"
                                type="text"
                                label="YouTube video URL"
                                autofocus
                            />
                        </div>


                        {{-- Submit --}}
                        <x-ui.button type="submit" variant="primary" size="sm">
                           Continue
                        </x-ui.button>


                    </form>



                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

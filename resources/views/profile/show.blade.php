{{-- resources/views/admin/profile/show.blade.php --}}

@php
    $locked = blank($user->display_name);
@endphp

<x-layouts.app
    title="Welcome aboard!"
    subtitle="One more step to create your account."
>

    {{-- Profile card --}}
    <x-ui.card class="max-w-5xl mx-auto px-16 py-16">

        {{-- Profile header --}}
        @include('profile.partials.header')

        {{-- DETAILS --}}
        <div class="mt-10 border-t border-zinc-400 flex flex-col font-light">

            {{-- Name --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Name:
                </span>
                <div class="col-span-7 text-right">
                    @if(filled($user->name))
                        <span>{{ $user->name }}</span>
                    @else
                        <x-ui.button variant="ghost"
                            type="button"
                            size="xs"
                            class="mt-0"
                            href="{{route('profile.edit')}}"
                        >
                            Add name
                        </x-ui.button>
                    @endif
                </div>
            </div>

            {{-- Username --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Username:
                </span>
                <div class="col-span-7 text-right">
                    @if(filled($user->display_name))
                        <span>{{ $user->display_name }}</span>
                    @else
                        <x-ui.button variant="ghost"
                            type="button"
                            size="xs"
                            class="mt-0!"
                            href="{{route('profile.edit')}}"
                        >
                            Add username
                        </x-ui.button>
                    @endif
                </div>
            </div>

            {{-- Email --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Email:
                </span>
                <div class="col-span-7 text-right">{{$user->email}}</div>
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Password:
                </span>
                <div class="col-span-7 text-right">********</div>
            </div>

            {{-- Bio --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Bio:
                </span>
                <div class="col-span-7 text-right">
                    @if(filled($user->bio))
                        <span>{{ $user->bio }}</span>
                    @else
                        <x-ui.button variant="ghost"
                            type="button"
                            size="xs"
                            class="mt-0!"
                            href="{{route('profile.edit')}}"
                        >
                            Add bio
                        </x-ui.button>
                    @endif
                </div>
            </div>

            {{-- Country --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Country:
                </span>
                <div class="col-span-7 text-right">
                    @if(filled($user->country_code))
                        <span>{{$user->country_name}}</span>
                    @else
                        <x-ui.button variant="ghost"
                            type="button"
                            size="xs"
                            class="mt-0!"
                            href="{{route('profile.edit')}}"
                        >
                            Add country
                        </x-ui.button>
                    @endif
                </div>
        
            </div>

            @if ($user->country_code === 'US')
                {{-- State --}}
                <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                    <span class="col-span-5 text-zinc-500 font-light">
                        State:
                    </span>
                    <div class="col-span-7 text-right">
                    @if(filled($user->state_code))
                        <span>{{$user->state_name}}</span>
                    @else
                        <x-ui.button variant="ghost"
                            type="button"
                            size="xs"
                            class="mt-0!"
                            href="{{route('profile.edit')}}"
                        >
                            Add state
                        </x-ui.button>
                    @endif
                </div>
                </div>
            @endif

        </div>

    </x-ui.card>
</x-layouts.app>


@if($locked)
    <div class="{{ $locked ? 'blur-none pointer-events-none fixed inset-0 z-90 bg-black/40 flex items-center justify-center' : '' }}">
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-100">
            <x-ui.card class="max-w-md w-full">
                <h1 class="text-2xl font-bold">Choose a username</h1>
                <p class="my-3 text-sm text-zinc-600">
                    This will be your public profile name.
                </p>
                <div class="flex rounded-md shadow-sm">
                    <span class="flex items-center px-3 border border-r-0 bg-green-50 text-gray-500">
                        @
                    </span>
                    <input
                        type="text"
                        class="w-full border px-3 py-2 outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="username"
                        value="{{ $user->username }}"
                    />
                </div>
                <x-ui.button class="mt-4 w-full">
                    Confirm username
                </x-ui.button>
            </x-ui.card>
        </div>
    </div>
@endif
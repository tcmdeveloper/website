{{-- resources/views/admin/profile/show.blade.php --}}

<x-layouts.app
    title="Profile"
    subtitle="Manage your account details and preferences."
>

    
    {{-- Profile card --}}
    <x-ui.card class="max-w-2xl mx-auto px-16 py-16">

        {{-- Profile header --}}
        @include('admin.profile.partials.header')

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
                            href="{{route('admin.profile.edit')}}"
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
                            href="{{route('admin.profile.edit')}}"
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
                            href="{{route('admin.profile.edit')}}"
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
                            href="{{route('admin.profile.edit')}}"
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
                            href="{{route('admin.profile.edit')}}"
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
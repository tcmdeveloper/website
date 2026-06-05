<x-layouts.app
    title="Change Password"
    subtitle="Update your account password securely."
>

    <x-ui.card class="max-w-2xl mx-auto px-16 py-16 pb-11">

        {{-- Profile header --}}
        @include('admin.profile.partials.header')

        <div class="mt-10 border-t border-zinc-400 flex flex-col font-light">

            <form
                method="POST"
                action="{{ route('admin.profile.password.update') }}"
                class=""
            >
                @csrf
                @method('PATCH')

                {{-- Current password --}}
                <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                    <span class="col-span-5 text-zinc-400 font-normal text-base">
                        Current password:
                    </span>
                    <div class="col-span-7">
                        <x-ui.input
                            name="current_password"
                            type="password"
                            size="sm"
                            label="Current password"
                            hideLabel=true
                            autofocus
                        />
                    </div>
                </div>

                {{-- Current password --}}
                <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                    <span class="col-span-5 text-zinc-400 font-normal text-base">
                        New password:
                    </span>
                    <div class="col-span-7">
                        <x-ui.input
                            name="password"
                            type="password"
                            size="sm"
                            label="Password"
                            hideLabel=true
                            autofocus
                        />
                    </div>
                </div>

                {{-- Current password --}}
                <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                    <span class="col-span-5 text-zinc-400 font-normal text-base">
                        Confirm new password:
                    </span>
                    <div class="col-span-7">
                        <x-ui.input
                            name="password_confirmation"
                            type="password"
                            size="sm"
                            label="Confirm new password"
                            hideLabel=true
                            autofocus
                        />
                    </div>
                </div>


                {{-- ACTIONS --}}
                <div class="flex gap-3 mt-8 justify-center">
                    <x-ui.button type="submit" variant="primary" size="md" full>Update Password</x-ui.button>
                    <x-ui.button href="{{route('admin.profile.show')}}" size="md" variant="secondary" full>Cancel</x-ui.button>
                </div>



               

             

            </form>
        </div>

    </x-ui.card>

</x-layouts.app>
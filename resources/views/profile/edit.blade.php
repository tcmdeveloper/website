


{{-- resources/views/admin/profile/show.blade.php --}}

<x-layouts.app
    title="Profile"
    subtitle="Manage your account details and preferences."
>

 <x-ui.card class="max-w-2xl mx-auto px-16 py-16 pb-11">


    @include('admin.profile.partials.header')


    <form
            method="POST"
            action="{{ route('admin.profile.update') }}"
            class="space-y-5"
            x-data="formHandler({
                firstError: {{ Js::from($errors->keys()[0] ?? null) }}
            })"
            x-init="init()"
        >

        {{-- DETAILS --}}
        <div
            x-data="{
                country: '{{ old('country_code', $user->country_code ?? '') }}',
                state: '{{ old('state_code', $user->state_code ?? '') }}',
                usStates: @js(config('us-states')),

                usernameError: null,
                usernameLocked: {{ $user->username ? 'true' : 'false' }},

                lockUsername() {
                    if (!this.usernameLocked) return;

                    this.usernameError = {
                        message: 'You cannot change your username.',
                        link: '/support'
                    };
                }
            }"
            class="mt-12 border-t border-zinc-400 flex flex-col text-lg"
        >

            

            @csrf
            @method('PATCH')

            {{-- Name --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Name:
                </span>
                <div class="col-span-7">
                    <x-ui.input
                        name="name"
                        type="text"
                        size="sm"
                        label="Name"
                        hideLabel=true
                        value="{{$user->name}}"
                        class="{{ $errors->has('name') ? 'bg-red-50 border-red-500 focus:ring-red-300' : '' }}"
                    />
                </div>
            </div>

            {{-- Username --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Username:
                </span>
                <div class="col-span-7">
                    <div @click="usernameLocked && lockUsername()">
                        <x-ui.input
                            name="username"
                            type="text"
                            size="sm"
                            label="Username"
                            hideLabel="true"
                            value="{{ $user->display_name ?? '' }}"
                            :readonly="$user->display_name ? true : false"
                            class="w-full {{ $user->display_name ? 'cursor-not-allowed' : '' }}"
                        />
                    </div>

                    @if (! $errors->has('username'))
                        <p
                            x-show="!usernameLocked"
                            class="mt-2 text-xs text-zinc-400"
                        >
                            You can set your username once.
                        </p>
                    @endif

                    <p
                        x-show="usernameError"
                        x-transition
                        class="mt-2 text-xs text-red-500"
                    >
                        <span x-text="usernameError.message"></span>
                        <a :href="usernameError.link" class="link">
                            click here
                        </a>
                    </p>
                </div>

            </div>

            {{-- Email --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Email:
                </span>
                <div class="col-span-7">
                    <x-ui.input
                        name="email"
                        type="text"
                        inputmode="email"
                        size="sm"
                        label="Email"
                        hideLabel=true
                        value="{{$user->email}}"
                    />
                </div>
            </div>

            {{-- Bio --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Bio:
                </span>
                <div class="col-span-7">
                    <x-ui.textarea
                        name="bio"
                        type="text"
                        size="sm"
                        label="Bio"
                        hideLabel=true
                        value="{!! $user->bio !!}"
                    />
                </div>
            </div>

            {{-- Country --}}
            <div class="grid grid-cols-12 items-center border-b border-zinc-300 py-8">
                <span class="col-span-5 text-zinc-500 font-light">
                    Country:
                </span>
                <div class="col-span-7">
                    <x-ui.select
                        name="country_code"
                        size="sm"
                        label="Country"
                        placeholder="Select your country..."
                        hideLabel="true"
                        :options="config('countries')"
                        x-model="country"
                    />
                </div>
            </div>

            {{-- State --}}
            <div
                class="grid grid-cols-12 items-center border-b border-zinc-300 py-8"
                x-show="country === 'US'"
                x-transition
            >
                <span class="col-span-5 text-zinc-500 font-light">
                    State:
                </span>

                <div class="col-span-7">
                    <x-ui.select
                        name="state_code"
                        size="sm"
                        label="State"
                        placeholder="Select your state..."
                        hideLabel="true"
                        :options="config('states')"
                        x-bind:options="usStates"
                        x-model="state"
                    />
                </div>
            </div>


        </div>

        {{-- ACTIONS --}}
        <div class="flex gap-3 mt-8 justify-center">
            <x-ui.button type="submit" variant="primary" size="md" full>Save changes</x-ui.button>
            <x-ui.button href="{{route('admin.profile.show')}}" size="md" variant="secondary" full>Cancel</x-ui.button>
        </div>

    </form>

    {{-- CROPPER MODAL (MUST BE INSIDE SAME x-data) --}}
    <div
        x-show="cropperOpen"
        x-transition
        x-cloak
        x-init="$watch('cropperOpen', value => {
            if (!value && cropper) {
                cropper.destroy();
                cropper = null;
            }
        })"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/80"
    >
        <div class="bg-white p-6 rounded-lg w-[90%] max-w-xl">

            <img
                x-ref="cropperImage"
                :src="imageSrc"
                class="max-h-[500px] w-full"
            >

            <div class="mt-4 flex justify-end gap-3">
                <button type="button" @click="closeCropper">
                    Cancel
                </button>

                <button type="button" @click="saveCrop">
                    Save
                </button>
            </div>

        </div>
    </div>

</x-ui.card>

</x-layouts.app>

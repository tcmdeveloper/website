{{-- resources/views/admin/profile/partials/header.blade.php --}}

<div x-data="profileAvatar">

    {{-- TOP ROW --}}
    <div class="flex items-start gap-4">

        {{-- Avatar --}}
        <div class="flex flex-col gap-3 items-center">
            <div class="relative group">
                <img
                    src="{{ $user->avatar_url }}"
                    alt="Profile image"
                    class="w-24 h-24 rounded-full cursor-pointer object-cover ring-2 ring-sky-600 ring-offset-2"
                >

                <button
                    type="button"
                    @click="$refs.avatarInput.click()"
                    class="absolute inset-0 flex items-center justify-center
                        rounded-full bg-black/40 opacity-0
                        transition-opacity group-hover:opacity-100 cursor-pointer"
                    aria-label="Change image"
                >
                    <x-heroicon-o-pencil class="h-8 w-8 text-white" />
                </button>
            </div>

            <x-ui.text-button
                type="button"
                class="text-xs"
                @click="$refs.avatarInput.click()"
                @change="console.log('file selected', $event.target.files)"
            >
                Change image
            </x-ui.text-button>

            {{-- FILE INPUT --}}
            <input
                x-ref="avatarInput"
                type="file"
                accept="image/*"
                class="hidden"
                @change="handleAvatarSelected($event)"
            >


        </div>

        {{-- User info --}}
        <div class="grow text-right">
            <h2 class="mt-2 text-3xl font-semibold text-gray-900">
                {{ $user->display_name ?: $user->name }}
            </h2>

            <p class="text-base text-zinc-400">
                {{ $user->email }}
            </p>

            {{-- ACTIONS --}}
            <div class="flex gap-3 justify-self-end mt-2">
                @if (request()->routeIs('admin.profile.edit') ||
                    request()->routeIs('admin.profile.password.edit'))
                    <x-ui.button
                        href="{{ route('admin.profile.show') }}"
                        size="xs"
                        variant="ghost"
                    >
                        Back to profile
                    </x-ui.button>
                @else
                    <x-ui.button
                        href="{{ route('admin.profile.edit') }}"
                        size="xs"
                    >
                        Edit profile
                    </x-ui.button>
                @endif
                <x-ui.button href="{{ route('admin.profile.password.edit')}}" size="xs" variant="secondary">Change password</x-ui.button>
            </div>
        </div>

    </div>



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
        <div class="bg-white p-6 rounded-sm w-fit max-w-md">
            <div class="w-[400px] max-w-full mx-auto">
    <img
        x-ref="cropperImage"
        :src="imageSrc"
        class="block max-w-full"
    >
</div>
            <div class="mt-4 flex justify-end gap-3">
                <x-ui.button type="button" variant="secondary" size="sm" @click="closeCropper">
                    Cancel
                </x-ui.button>

                <x-ui.button type="button" variant="primary" size="sm" @click="saveCrop">
                    Save
                </x-ui.button>

              
            </div>

        </div>
    </div>

    
</div>
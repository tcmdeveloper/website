@push('scripts')
@vite('resources/js/profile-avatar.js')
@endpush

<x-layouts.app
    title="Profile"
    subtitle="Manage your account details and preferences."
>

<div
    x-data="{
        cropper: null,
        cropperOpen: false,
        imageSrc: null,

        openFilePicker() {
            this.$refs.avatar.click()
        },

        handleAvatarSelected(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.cropperOpen = true;

                this.$nextTick(() => {
                    const image = this.$refs.cropperImage;

                    if (this.cropper) {
                        this.cropper.destroy();
                    }

                    this.cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                    });
                });
            };

            reader.readAsDataURL(file);
        },

        closeCropper() {
            this.cropperOpen = false;

            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        saveCrop() {
            const canvas = this.cropper.getCroppedCanvas({
                width: 400,
                height: 400
            });

            canvas.toBlob((blob) => {
                const formData = new FormData();
                formData.append('avatar', blob, 'avatar.jpg');

                fetch('/admin/profile/avatar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name=csrf-token]')
                            .content
                    },
                    body: formData
                }).then(() => {
                    window.location.reload();
                });

            }, 'image/jpeg', 0.9);
        }
    }"
    class="max-w-2xl mx-auto px-6 py-10 space-y-10 border rounded-sm"
>

    {{-- TOP ROW --}}
    <div class="flex flex-col items-center gap-4 bg-yellow-400">
        <div class="flex bg-red-400 w-full items-center">
            {{-- Avatar --}}
            <img
                src="{{ $user->avatar_url }}"
                alt="{{ $user->name }}"
                class="h-20 w-20 rounded-full object-cover border"
            >
            {{-- User info --}}
            <div class="grow text-right">
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $user->display_name ?: $user->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $user->email }}
                </p>                
            </div>
        </div>
        <div class="bg-blue-400">
            <x-ui.text-button
                type="button"
                @click="openFilePicker"
            >
                Change photo
            </x-ui.text-button>
        </div>

        {{-- FILE INPUT --}}
        <input
            x-ref="avatar"
            type="file"
            accept="image/*"
            class="hidden"
            @change="handleAvatarSelected($event)"
        >

        {{-- CROPPER MODAL --}}
        <div
            x-show="cropperOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80"
        >
            <div class="bg-white p-6 rounded-lg w-[90%] max-w-xl">

                <img
                    x-ref="cropperImage"
                    :src="imageSrc"
                    class="max-h-[500px] w-full"
                >

                <div class="mt-4 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeCropper"
                        class="text-sm text-gray-500"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        @click="saveCrop"
                        class="text-sm text-blue-600 font-medium"
                    >
                        Save
                    </button>
                </div>

            </div>
        </div>
    </div>

    

</div>

























    <div class="max-w-4xl mx-auto py-10 space-y-10">

        {{-- PROFILE HEADER --}}
        <div class="flex items-center gap-6">
            <div class="h-16 w-16 rounded-full bg-gray-200 overflow-hidden">
                <img
                    src="{{ $user->avatar_url }}"
                    alt="{{ $user->name }}"
                    class="h-full w-full object-cover rounded-full"
                >
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $user->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $user->email }}
                </p>
            </div>
        </div>

        {{-- PROFILE GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PERSONAL INFO --}}
            <div class="bg-white shadow-sm border rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold">Personal Information</h3>

                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Bio</p>
                    <p class="text-gray-700">
                        {{ $user->bio ?? 'No bio added yet.' }}
                    </p>
                </div>
            </div>

            {{-- ACCOUNT ACTIONS --}}
            <div class="bg-white shadow-sm border rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold">Account</h3>

                <a href="{{ route('admin.profile.edit') }}"
                   class="block px-4 py-2 rounded bg-blue-600 text-white text-center hover:bg-blue-700 transition">
                    Edit Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full px-4 py-2 rounded border border-gray-300 hover:bg-gray-50 transition"
                    >
                        Log out
                    </button>
                </form>
            </div>

        </div>

        {{-- OPTIONAL: EXTRA SECTION --}}
        <div class="bg-white shadow-sm border rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Activity</h3>

            <p class="text-gray-500 text-sm">
                Recent activity will appear here.
            </p>
        </div>

    </div>
</x-layouts.app>


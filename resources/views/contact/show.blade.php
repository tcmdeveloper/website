<x-layouts.app title="Contact Us" subtitle="We’d love to hear from you">

    <div class="max-w-2xl mx-auto">

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <x-ui.card class="p-6">

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="text-sm text-zinc-600">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full border rounded px-3 py-2"
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="text-sm text-zinc-600">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full border rounded px-3 py-2"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subject --}}
                <div>
                    <label class="text-sm text-zinc-600">Subject</label>
                    <input
                        type="text"
                        name="subject"
                        value="{{ old('subject') }}"
                        class="w-full border rounded px-3 py-2"
                    >
                    @error('subject')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Message --}}
                <div>
                    <label class="text-sm text-zinc-600">Message</label>
                    <textarea
                        name="message"
                        rows="5"
                        class="w-full border rounded px-3 py-2"
                    >{{ old('message') }}</textarea>

                    @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <x-ui.button type="submit" class="w-full">
                    Send Message
                </x-ui.button>

            </form>

        </x-ui.card>

    </div>

</x-layouts.app>
{{-- resources/views/contact/show.blade.php --}}

<x-layouts.app 
    title="Contact me" 
    subtitle="Send me a message and I'll get back to you as soon as I can."
    :meta="[
        'title' => 'Contact me',
        'description' => 'Get in touch with us for any questions, support, or feedback. We\'re here to help and will respond as soon as possible.'
    ]"
>

    {{-- Page container --}}
    
    <x-ui.container class="max-w-3xl">

        @if(session('success'))

            <div class="text-center">
                <div class="mb-4 p-3 rounded bg-green-100 text-green-600 text-center text-sm border border-green-400 shadow-sm">
                    {{ session('success') }}
                </div>
                <x-ui.button href="/">Back to homepage</x-ui.button>
            </div>

        @else

            {{-- Link to email --}}

            <x-ui.card class="p-6 my-0! flex justify-center">
                <a href="mailto:metrix@truecrimemetrix.com" class="text-2xl text-zinc-600 font-bold hover:text-yellow-500">
                    <x-ui.icon name="envelope" size="lg" class="mr-2" /> metrix@truecrimemetrix.com
                </a>
            </x-ui.card>


            {{-- Divider with text --}}

            <div class="flex items-center gap-4 py-6 form-text" role="separator">
                <div class="flex-1 border-t border-stone-300"></div>
                <span class="text-sm italic font-light">
                    or get in touch using the form below
                </span>
                <div class="flex-1 border-t border-stone-300"></div>
            </div>        
            
            
            
            {{-- Contact us form --}}

            <x-ui.card class="p-6">

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">

                    @csrf

                    {{-- Name --}}
                    <div>
                        <x-ui.input
                            name="name"
                            type="text"
                            label="Name"
                            placeholder="Your name"
                        />
                    </div>


                    {{-- Email --}}
                    <div>
                        <x-ui.input
                            name="email"
                            type="text"
                            inputmode="email"
                            label="Email"
                            placeholder="Email"
                        />
                    </div>


                    {{-- Subject --}}
                    <div>
                        <x-ui.input
                            name="subject"
                            type="text"
                            label="Subject"
                            placeholder="Subject"
                        />
                    </div>


                    {{-- Message --}}
                    <x-ui.textarea
                        name="message"
                        label="Message"
                        rows="6"
                        placeholder="Write message..."
                    />


                    {{-- Submit --}}

                    <x-ui.button type="submit" class="w-full">
                        Send Message
                    </x-ui.button>


                </form>


            </x-ui.card>


        @endif


    </x-ui.container>


</x-layouts.app>
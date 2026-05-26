{{-- resources/views/auth/register.blade.php --}}

<x-layouts.app 
    title="Create an account" 
    subtitle="Use your Google account or enter your email & password manually."
>


    {{-- Authentication status messages --}}
    <x-auth-session-status :status="session('status')" />

    <x-ui.card class="max-w-lg mx-auto text-stone-600 text-sm">

        {{-- Sign in with Google button --}}
        <x-ui.google-button href="{{ route('google.redirect') }}">
            Sign in with Google
        </x-ui.google-button>

        {{-- Divider with text --}}
        <x-ui.divider-with-text text="or sign in with email" />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            
            @csrf

            {{-- Name --}}
            <div>
                <x-ui.input
                    name="name"
                    type="text"
                    label="Name"
                />
            </div>

            {{-- Email --}}
            <div>
                <x-ui.input
                    name="email"
                    type="email"
                    label="Email"
                />
            </div>

            {{-- Password --}}
            <div>
                <x-ui.input
                    name="password"
                    type="password"
                    label="Password"
                />
            </div>

            {{-- Confirm Password --}}
            <div>
                <x-ui.input
                    name="password_confirmation"
                    type="password"
                    label="Confirm password"
                />
            </div>

            {{-- Terms acceptance --}}
            <div>
                <x-ui.checkbox name="terms">
                    I agree to the

                    <a
                        href="{{ route('terms') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="link"
                    >Terms of Service</a>

                    and

                    <a
                        href="{{ route('privacy') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="link"
                    >
                        Privacy Policy
                    </a>
                </x-ui.checkbox>
            </div>

            {{-- Divider --}}
            <x-ui.divider />

            {{-- Submit --}}
            <x-ui.button type="submit" size="lg" full class="">
                Create account
            </x-ui.button>

        </form>

        {{-- Form footer --}}
        <x-ui.form-footer>
            Already have an account?
            <a href="{{ route('login') }}" class="link">
                Go to login
            </a>
        </x-ui.form-footer>

    </x-ui.card>

</x-layouts.app>
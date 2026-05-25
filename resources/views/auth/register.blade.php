{{-- resources/views/auth/register.blade.php --}}

<x-layouts.app 
    title="Create an account" 
    subtitle="Use your Google account or enter your email & password manually."
>

    <x-ui.card class="max-w-md mx-auto">


        <x-ui.google-button
            href="{{ route('google.redirect') }}"
        />

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            
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

            {{-- Submit --}}
            <x-ui.button type="submit" variant="success" size="lg" full>
                Create account
            </x-ui.button>

        </form>

        {{-- Form footer --}}
        <x-ui.form-footer>
            Already have an account?
            <a href="{{ route('login') }}" class="link">
                Login
            </a>
        </x-ui.form-footer>

    </div>

</x-layouts.app>
{{-- resources/views/auth/login.blade.php --}}

<x-layouts.app 
    title="Log in"
    subtitle="Use your Google account or enter your email & password manually."
>

    {{-- Authentication status messages --}}
    <x-auth-session-status :status="session('status')" />

    <x-ui.card class="max-w-lg mx-auto text-stone-600 text-sm">

        {{-- Sign in with Google button --}}
        <x-ui.google-button 
            href="{{ route('google.redirect') }}"
        >
            Sign in with Google
        </x-ui.google-button>

        {{-- Divider with text --}}
        <x-ui.divider-with-text text="or sign in with email" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

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

            {{-- Remember me --}}
            <div>
                <x-ui.checkbox
                    name="remember"
                    :label="__('Remember me')"
                />
            </div>

            {{-- Divider --}}
            <x-ui.divider />

            {{-- Submit --}}
            <x-ui.button type="submit" variant="primary" size="lg" full>
                Log in
            </x-ui.button>

        </form>

        {{-- Form footer --}}
        <x-ui.form-footer class="items-start">
            <a href="{{ route('password.request') }}" class="link">
                {{ __('Forgot your password?') }}
            </a>
            <a href="{{ route('register') }}" class="link">
                Create an account
            </a>
        </x-ui.form-footer>

    </x-ui.card>

</x-layouts.app>

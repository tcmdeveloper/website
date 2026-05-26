{{-- resources/views/auth/forgot-password.blade.php --}}

<x-layouts.app
    title="Forgot your password?"
    subtitle="Enter your email address and we'll send you a password reset link."
>

    {{-- Authentication status messages --}}
    <x-auth-session-status :status="session('status')" />

    <x-ui.card class="max-w-lg mx-auto text-stone-600 text-sm">

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            
            @csrf

            {{-- Email --}}
            <div>
                <x-ui.input
                    name="email"
                    type="email"
                    label="Email"
                    autofocus
                />
            </div>


            {{-- Submit --}}
            <x-ui.button type="submit" variant="primary" size="lg" full>
                {{ __('Email password reset link') }}
            </x-ui.button>

        </form>

        {{-- Form footer --}}
            <x-ui.form-footer class="items-start">
                <a href="{{ route('login') }}" class="link">
                    {{ __('Back to login') }}
                </a>
                <a href="{{ route('register') }}" class="link">
                    Create an account
                </a>
            </x-ui.form-footer>

    </x-ui.card>

</x-guest-layout>

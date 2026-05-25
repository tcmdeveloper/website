{{-- resources/views/auth/login.blade.php --}}

<x-layouts.app 
    title="Log in"
    subtitle="Use your Google account or enter your email & password manually."
>

    <!-- Session status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


    <x-ui.card class="max-w-md mx-auto">

        {{-- Sign in with Google button --}}
        <x-ui.google-button
            href="{{ route('google.redirect') }}"
        />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <x-ui.input
                    name="email"
                    type="email"
                    label="Email"
                />
            </div>

            <!-- Password -->
            <div>
                <x-ui.input
                    name="email"
                    type="email"
                    label="Email"
                />
            </div>
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>



                <x-ui.form-footer class="items-start">
                <a 
                    href="{{ route('password.request') }}" 
                    class="btn-link"
                >
                    Forgot your password?
                </a>

                <a 
                    href="{{ route('register') }}"
                    class="btn-link"    
                >
                    Create an account
                </a>
            </x-ui.form-footer>



            </div>
        </form>
    </x-ui.card>
</x-layouts.app>

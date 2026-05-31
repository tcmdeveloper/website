{{-- resources/views/auth/register.blade.php --}}

<x-layouts.app 
    title="Create an account" 
    subtitle="Use your Google account or enter your email & password manually."
>



    {{-- Authentication status messages --}}
    <x-auth-session-status :status="session('status')" class="max-w-lg mx-auto" />


    <x-ui.card class="max-w-lg mx-auto">


        {{-- Sign in with Google button --}}
        <x-ui.google-button href="{{ route('google.redirect') }}">
            Sign in with Google
        </x-ui.google-button>


        {{-- Divider with text --}}
        <x-ui.divider-with-text text="or sign in with email" />


        <form
            method="POST"
            action="{{ route('register.store') }}"
            class="space-y-4"
            x-data="formHandler({
                errors: {{ Js::from($errors->keys()) }},
                agreed: {{ old('terms') ? 'true' : 'false' }}
            })"
            x-init="init()"
        >
            
            @csrf


            {{-- Email --}}
            <div>
                <x-ui.input
                    name="email"
                    type="text"
                    inputmode="email"
                    label="Email"
                />
            </div>


            {{-- Username --}}
            <div>
                <x-ui.input
                    name="username"
                    type="text"
                    label="Username"
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

                    @if ($errors->has('terms'))
                        <span class="text-red-600">
                            I agree to the

                            <a
                                href="{{ route('pages.terms') }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="link text-red-800!"
                            >Terms of Service</a>

                            and

                            <a
                                href="{{ route('pages.privacy') }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="link text-rose-700!"
                            >
                                Privacy Policy
                            </a>
                        </span>
                    @else
                        <span>
                            I agree to the

                            <a
                                href="{{ route('pages.terms') }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="link"
                            >Terms of Service</a>

                            and

                            <a
                                href="{{ route('pages.privacy') }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="link"
                            >
                                Privacy Policy
                            </a>
                        </span>
                    @endif
                   
                </x-ui.checkbox>
            </div>


            {{-- Divider --}}
            <x-ui.divider />


            {{-- Submit --}}
            <x-ui.button
                type="submit"
                size="lg"
                full
                required
            >
                Create account
            </x-ui.button>


        </form>


        {{-- Form footer --}}

        <x-ui.form-footer>

            Already have an account?
            
            <a 
                href="{{ route('login') }}"
                class="link"
            >
                Go to login
            </a>

        </x-ui.form-footer>


    </x-ui.card>


</x-layouts.app>
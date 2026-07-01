{{-- resources/views/pages/privacy.blade.php --}}

<x-layouts.app
    title="Privacy Policy"
    subtitle="How we collect, use, and protect your information."
    :meta="[
        'title' => 'Privacy Policy',
        'description' => 'Read the Privacy Policy for True Crime Metrix to learn how we collect, use, store, and protect your personal information when you use our website and services.',
    ]"
>

    <x-ui.container class="max-w-3xl prose-content">

        {{-- Intro --}}
        <section>
            <h3>Introduction</h3>
            <p>
                Welcome to {{ config('app.name') }}. Your privacy is important to us.
                This Privacy Policy explains how we collect, use, and protect your data when using our website.
            </p>
        </section>

        {{-- Data collected --}}
        <section>
            <h3>Information We Collect</h3>

            <ul>
                <li>Name and email address</li>
                <li>Google OAuth profile information (if you sign in with Google)</li>
                <li>Contact form messages</li>
                <li>Technical data such as IP address, browser type, and device info</li>
                <li>Cookies and usage data</li>
            </ul>
        </section>

        {{-- Google login --}}
        <section>
            <h3>Google Sign-In</h3>
            <p>
                If you sign in using Google, we receive basic profile information such as your name and email address.
                We do not access your Gmail, Google Drive, or YouTube data unless explicitly authorized.
            </p>
        </section>

        {{-- Usage --}}
        <section>
            <h3>How We Use Your Data</h3>

            <ul>
                <li>To create and manage your account</li>
                <li>To authenticate users securely</li>
                <li>To improve website performance and experience</li>
                <li>To respond to messages and support requests</li>
            </ul>
        </section>

        {{-- Cookies --}}
        <section>
            <h3>Cookies</h3>
            <p>
                We use cookies to maintain sessions, improve functionality, and analyze site usage.
                You can disable cookies in your browser settings, but some features may stop working.
            </p>
        </section>

        {{-- Data sharing --}}
        <section>
            <h3>Data Sharing</h3>
            <p>
                We do not sell your personal data. We may share limited data with trusted providers such as:
            </p>

            <ul>
                <li>Authentication providers (e.g. Google OAuth)</li>
                <li>Hosting and infrastructure services</li>
                <li>Analytics providers</li>
            </ul>
        </section>

        {{-- Rights --}}
        <section>
            <h3>Your Rights</h3>

            <p>
                Depending on your location, you may request access, correction, or deletion of your data.
                You may also withdraw consent at any time.
            </p>
        </section>

        {{-- Security --}}
        <section>
            <h3>Security</h3>
            <p>
                We take reasonable measures to protect your data, but no system is 100% secure.
            </p>
        </section>

        <x-ui.divider />


        {{-- Contact --}}
        <section>
            <h3>Contact</h3>

            <p>
                If you have questions about these Terms, contact us at:
            </p>

            <p>
                <a href="mailto:metrix@truecrimemetrix.com" class="link"><x-ui.icon name="envelope" class="mr-2" />metrix@truecrimemetrix.com</a>
            </p>
        </section>

        {{-- Footer note --}}
        <p class="text-sm text-gray-500 pt-6">
            {{-- Last updated: {{ now()->format('F d, Y') }} --}}
            Last updated: May 05, 2026
        </p>

    </x-ui.container>

</x-layouts.app>
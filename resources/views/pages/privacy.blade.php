{{-- resources/views/pages/privacy.blade.php --}}

<x-layouts.app
    title="Privacy Policy"
    subtitle="How we collect, use, and protect your information."
>

    <div class="max-w-4xl mx-auto space-y-10 text-gray-700 leading-relaxed">

        {{-- Intro --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Introduction</h2>
            <p class="mt-2">
                Welcome to {{ config('app.name') }}. Your privacy is important to us.
                This Privacy Policy explains how we collect, use, and protect your data when using our website.
            </p>
        </section>

        {{-- Data collected --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Information We Collect</h2>

            <ul class="mt-3 list-disc pl-6 space-y-2">
                <li>Name and email address</li>
                <li>Google OAuth profile information (if you sign in with Google)</li>
                <li>Contact form messages</li>
                <li>Technical data such as IP address, browser type, and device info</li>
                <li>Cookies and usage data</li>
            </ul>
        </section>

        {{-- Google login --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Google Sign-In</h2>
            <p class="mt-2">
                If you sign in using Google, we receive basic profile information such as your name and email address.
                We do not access your Gmail, Google Drive, or YouTube data unless explicitly authorized.
            </p>
        </section>

        {{-- Usage --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">How We Use Your Data</h2>

            <ul class="mt-3 list-disc pl-6 space-y-2">
                <li>To create and manage your account</li>
                <li>To authenticate users securely</li>
                <li>To improve website performance and experience</li>
                <li>To respond to messages and support requests</li>
            </ul>
        </section>

        {{-- Cookies --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Cookies</h2>
            <p class="mt-2">
                We use cookies to maintain sessions, improve functionality, and analyze site usage.
                You can disable cookies in your browser settings, but some features may stop working.
            </p>
        </section>

        {{-- Data sharing --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Data Sharing</h2>
            <p class="mt-2">
                We do not sell your personal data. We may share limited data with trusted providers such as:
            </p>

            <ul class="mt-3 list-disc pl-6 space-y-2">
                <li>Authentication providers (e.g. Google OAuth)</li>
                <li>Hosting and infrastructure services</li>
                <li>Analytics providers</li>
            </ul>
        </section>

        {{-- Rights --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Your Rights</h2>

            <p class="mt-2">
                Depending on your location, you may request access, correction, or deletion of your data.
                You may also withdraw consent at any time.
            </p>
        </section>

        {{-- Security --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Security</h2>
            <p class="mt-2">
                We take reasonable measures to protect your data, but no system is 100% secure.
            </p>
        </section>

        {{-- Contact --}}
        <section class="pt-6 border-t">
            <h2 class="text-xl font-semibold text-gray-900">Contact</h2>
            <p class="mt-2">
                If you have questions about this Privacy Policy, contact us at:
            </p>

            <p class="mt-3 font-medium text-olive-600">
                <a href="mailto:metrix@truecrimemetrix.com">metrix@truecrimemetrix.com</a>
            </p>
        </section>

        {{-- Footer note --}}
        <p class="text-sm text-gray-500 pt-6">
            Last updated: {{ now()->format('F d, Y') }}
        </p>

    </div>

</x-layouts.app>
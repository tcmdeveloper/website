{{-- resources/views/pages/terms.blade.php --}}

<x-layouts.app
    title="Terms of Service"
    subtitle="Rules, responsibilities, and conditions for using this website."
>

    <div class="max-w-3xl mx-auto space-y-10 text-gray-700 leading-relaxed">

        {{-- Introduction --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Introduction</h2>
            <p class="mt-2">
                Welcome to {{ config('app.name') }}. By accessing or using this website,
                you agree to be bound by these Terms of Service. If you do not agree,
                you should not use the website.
            </p>
        </section>

        {{-- Use of site --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Use of the Website</h2>

            <ul class="mt-3 list-disc pl-6 space-y-2">
                <li>You agree to use this website only for lawful purposes.</li>
                <li>You must not attempt to disrupt or misuse the service.</li>
                <li>You must not attempt unauthorized access to any part of the system.</li>
            </ul>
        </section>

        {{-- Accounts --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Accounts</h2>
            <p class="mt-2">
                When you create an account, you are responsible for maintaining the confidentiality
                of your login credentials and all activity under your account.
            </p>

            <p class="mt-3">
                We reserve the right to suspend or terminate accounts that violate these terms.
            </p>
        </section>

        {{-- Google login --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Google Authentication</h2>
            <p class="mt-2">
                If you sign in using Google, you authorize us to receive basic profile information
                such as your name, email address, and profile image.
            </p>
        </section>

        {{-- Content --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Content</h2>

            <ul class="mt-3 list-disc pl-6 space-y-2">
                <li>All content provided on this site is for informational purposes only.</li>
                <li>We do not guarantee accuracy, completeness, or reliability of content.</li>
                <li>Content may be updated or removed at any time without notice.</li>
            </ul>
        </section>

        {{-- Prohibited use --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Prohibited Activities</h2>

            <ul class="mt-3 list-disc pl-6 space-y-2">
                <li>Hacking, scraping, or reverse engineering the platform</li>
                <li>Uploading malicious code or attempting to harm the system</li>
                <li>Using the service for illegal or abusive activities</li>
            </ul>
        </section>

        {{-- Liability --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Limitation of Liability</h2>
            <p class="mt-2">
                We are not liable for any damages arising from the use or inability to use this website.
                The service is provided “as is” without warranties of any kind.
            </p>
        </section>

        {{-- Changes --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-900">Changes to Terms</h2>
            <p class="mt-2">
                We may update these Terms of Service at any time. Continued use of the website
                after changes means you accept the updated terms.
            </p>
        </section>

        {{-- Contact --}}
        <section class="pt-6 border-t">
            <h2 class="text-xl font-semibold text-gray-900">Contact</h2>

            <p class="mt-2">
                If you have questions about these Terms, contact us at:
            </p>

            <p class="mt-3 font-medium text-olive-600">
                <a href="mailto:metrix@truecrimemetrix.com">metrix@truecrimemetrix.com</a>
            </p>
        </section>

        {{-- Footer --}}
        <p class="text-sm text-gray-500 pt-6">
            Last updated: {{ now()->format('F d, Y') }}
        </p>

    </div>

</x-layouts.app>
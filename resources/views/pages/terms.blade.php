{{-- resources/views/pages/terms.blade.php --}}

<x-layouts.app
    title="Terms of Service"
    subtitle="Rules, responsibilities, and conditions for using this website."
    :meta="[
        'title' => 'Terms of Service',
        'description' => 'Read the Terms & Conditions for True Crime Metrix. Learn about the rules, responsibilities, acceptable use, and legal terms that apply when using our website and services.',
    ]"
>

    <x-ui.container class="max-w-3xl prose-content">

        {{-- Introduction --}}
        <section>
            <h3>Introduction</h3>
            <p>
                Welcome to {{ config('app.name') }}. By accessing or using this website,
                you agree to be bound by these Terms of Service. If you do not agree,
                you should not use the website.
            </p>
        </section>


        {{-- Use of site --}}
        <section>
            <h3>Use of the Website</h3>
            <ul>
                <li>You agree to use this website only for lawful purposes.</li>
                <li>You must not attempt to disrupt or misuse the service.</li>
                <li>You must not attempt unauthorized access to any part of the system.</li>
            </ul>
        </section>


        {{-- Accounts --}}
        <section>
            <h3>Accounts</h3>
            <p>
                When you create an account, you are responsible for maintaining the confidentiality
                of your login credentials and all activity under your account.
            </p>

            <p>
                We reserve the right to suspend or terminate accounts that violate these terms.
            </p>
        </section>


        {{-- Google login --}}
        <section>
            <h3>Google Authentication</h3>
            <p>
                If you sign in using Google, you authorize us to receive basic profile information
                such as your name, email address, and profile image.
            </p>
        </section>


        {{-- Content --}}
        <section>
            <h3>Content</h3>
            <ul>
                <li>All content provided on this site is for informational purposes only.</li>
                <li>We do not guarantee accuracy, completeness, or reliability of content.</li>
                <li>Content may be updated or removed at any time without notice.</li>
            </ul>
        </section>


        {{-- Prohibited use --}}
        <section>
            <h3>Prohibited Activities</h3>
            <ul>
                <li>Hacking, scraping, or reverse engineering the platform</li>
                <li>Uploading malicious code or attempting to harm the system</li>
                <li>Using the service for illegal or abusive activities</li>
            </ul>
        </section>


        {{-- Liability --}}
        <section>
            <h3>Limitation of Liability</h3>
            <p>
                We are not liable for any damages arising from the use or inability to use this website.
                The service is provided “as is” without warranties of any kind.
            </p>
        </section>


        {{-- Changes --}}
        <section>
            <h3>Changes to Terms</h3>
            <p class="mt-2">
                We may update these Terms of Service at any time. Continued use of the website
                after changes means you accept the updated terms.
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


        <x-ui.divider />


        {{-- Footer --}}
        <p class="text-sm text-zinc-400">
            {{-- Last updated: {{ now()->format('F d, Y') }} --}}
            Last updated: May 05, 2026
        </p>

        
    </x-ui.container>


</x-layouts.app>
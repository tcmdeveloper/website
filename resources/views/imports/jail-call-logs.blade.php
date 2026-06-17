<x-layouts.app
    title="Import Jail Call Log"
    subtitle="Select your CSV file of the jail call log."
>
    <x-ui.card class="mx-auto mt-10 max-w-5xl">
        @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            <p style="color:red">{{ $errors->first() }}</p>
        @endif

        <form method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file" required>
            <button type="submit">Import CSV</button>
        </form>
    </x-ui.card>
</x-layouts.app>
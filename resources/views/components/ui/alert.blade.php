{{-- resources/views/components/ui/alerts.blade.php --}}

@if (session('status'))
        
    @php($status = session('status'))

    <div
        class="mb-6 rounded-md border px-4 py-3
            {{ $status['type'] === 'success'
                ? 'border-green-300 bg-green-50 text-green-800'
                : 'border-red-300 bg-red-50 text-red-800' }}"
    >
        {{ $status['message'] }}
    </div>

@endif
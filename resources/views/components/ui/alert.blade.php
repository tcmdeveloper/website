{{-- resources/views/components/ui/alerts.blade.php --}}

@props([
    'type' => null,
    'message' => null
])

@if (session('status'))
        
    @php($status = session('status'))

    <div
        class="mb-6 rounded-md border px-4 py-3 text-sm
            {{ $status['type'] === 'success'
                ? 'border-green-300 bg-green-50 text-green-800'
                : 'border-red-300 bg-red-50 text-red-800' }}"
    >
        {{ $status['message'] }}
    </div>

@elseif($type !== null && $message !== null)

    <div
        class="mb-6 rounded-md border px-4 py-3 text-sm
            {{ $type === 'success'
                ? 'border-green-300 bg-green-50 text-green-800'
                : 'border-red-300 bg-red-50 text-red-800' }}"
    >
        {{ $message }}
    </div>

@endif

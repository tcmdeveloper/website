@props([
    'status',
    'type' => session('type', 'error')
])

@if ($status)
    <div {{
        $attributes
            ->class([
                'flex justify-start items-center gap-3 mb-10 px-4 py-3 rounded-lg shadow-sm text-sm font-medium border',
                'border-lime-300 bg-green-50 text-green-700' => $type === 'success',
                'border-rose-300 bg-red-50 text-red-700' => $type === 'error',
                'border-amber-300 bg-yellow-50 text-yellow-700' => $type === 'warning',
                'border-sky-300 bg-blue-50 text-blue-700' => $type === 'info',
            ])
            ->merge([
                'role' => 'alert'

            ])
    }}>
   
        {{-- Icon --}}
        @if ($type === 'success')
            <x-heroicon-o-check-circle class="mt-0.5 w-6 aspect-square" />
        @elseif ($type === 'error')
            <x-heroicon-o-x-circle class="mt-0.5 w-6 aspect-square" />
        @elseif ($type === 'warning')
            <x-heroicon-o-exclamation-triangle class="mt-0.5 w-6 aspect-square" />
        @elseif ($type === 'info')
            <x-heroicon-o-information-circle class="mt-0.5 w-6 aspect-square" />
        @endif

        <div class="leading-relaxed">
            {{ $status }}
        </div>

    </div>
@endif


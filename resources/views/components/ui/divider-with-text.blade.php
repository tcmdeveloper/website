{{-- resources/views/components/ui/divider-with-text.blade.php --}}

@props([
    'text' => 'or continue with',
])

<div class="flex items-center gap-4 py-6 form-text" role="separator">
    <div class="flex-1 border-t border-stone-300"></div>
    <span class="text-xs font-medium">
        {{ $text }}
    </span>
    <div class="flex-1 border-t border-stone-300"></div>
</div>
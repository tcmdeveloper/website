{{-- resources/views/components/ui/form-footer.blade.php --}}

<div {{ $attributes->merge([
    'class' => 'flex flex-col items-center gap-1 mt-8 text-sm text-gray-600 font-medium'
]) }}>
    {{ $slot }}
</div>
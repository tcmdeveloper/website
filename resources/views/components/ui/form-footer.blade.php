{{-- resources/views/components/ui/form-footer.blade.php --}}

<div {{ 
    $attributes->merge([
        'class' => 'flex flex-col items-center gap-1 mt-5 text-sm form-footer'
    ]) 
}}>
    {{ $slot }}
</div>
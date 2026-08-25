@props(['value'])

<div x-show="active === @js($value)" x-cloak role="tabpanel" data-slot="tabs-content" tabindex="0"
    {{ $attributes->merge([
        'class' => 'flex-1 text-sm outline-none',
    ]) }}>
    {{ $slot }}
</div>

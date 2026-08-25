@props([
    'default' => null,
    'orientation' => 'horizontal',
])

<div x-data="{
    active: @js($default),
    orientation: @js($orientation),
}" data-slot="tabs" data-orientation="{{ $orientation }}"
    {{ $attributes->merge([
        'class' => 'group/tabs flex gap-2 data-horizontal:flex-col',
    ]) }}>
    {{ $slot }}
</div>

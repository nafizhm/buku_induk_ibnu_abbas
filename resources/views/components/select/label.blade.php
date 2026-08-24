{{--
    <x-select.label> — setara <SelectLabel> (SelectPrimitive.GroupLabel).
--}}
@props([])

<div data-slot="select-label" {{ $attributes->class(['px-1.5 py-1 text-xs text-muted-foreground']) }}>
    {{ $slot }}
</div>

{{--
    <x-select.separator> — setara <SelectSeparator> (SelectPrimitive.Separator).
--}}
@props([])

<li data-slot="select-separator" role="separator" {{ $attributes->class(['pointer-events-none -mx-1 my-1 h-px bg-border list-none']) }}></li>

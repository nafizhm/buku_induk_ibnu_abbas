{{--
    <x-select.group> — setara <SelectGroup> (SelectPrimitive.Group).
--}}
@props([])

<li data-slot="select-group" role="group" {{ $attributes->class(['scroll-my-1 p-1 list-none']) }}>
    <ul class="m-0 list-none p-0">
        {{ $slot }}
    </ul>
</li>

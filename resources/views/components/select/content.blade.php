{{--
    <x-select.content> — setara <SelectContent> (SelectPrimitive.Portal > Positioner > Popup).

    Di Alpine kita tidak pakai portal, cukup absolute-positioned relatif ke root <x-select>
    yang sudah `relative`. Kelas diambil persis dari Popup di React version, termasuk
    hook animasi data-open / data-closed / data-side dan data-align-trigger.

    Scroll up/down button (SelectScrollUpButton / SelectScrollDownButton) otomatis
    muncul saat konten bisa di-scroll (state scrollUp / scrollDown di root).
--}}
@props(['align' => 'start'])

@php
$alignClass = match ($align) {
    'end' => 'right-0',
    'center' => 'left-1/2 -translate-x-1/2',
    default => 'left-0',
};
@endphp

<div
    x-show="open"
    x-cloak
    @scroll="updateScroll($event.target)"
    x-init="$nextTick(() => updateScroll($refs.content))"
    x-ref="content"
    role="listbox"
    data-slot="select-content"
    data-align-trigger="true"
    :data-open="open ? '' : null"
    :data-closed="!open ? '' : null"
    {{ $attributes->class([
        'absolute z-50 mt-1 w-full min-w-36 max-h-72 origin-top overflow-x-hidden overflow-y-auto',
        'rounded-lg bg-popover text-popover-foreground shadow-md ring-1 ring-foreground/10 p-0',
        'isolate duration-100',
        'data-[align-trigger=true]:animate-none',
        'data-[side=bottom]:slide-in-from-top-2 data-[side=inline-end]:slide-in-from-left-2 data-[side=inline-start]:slide-in-from-right-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2',
        'data-open:animate-in data-open:fade-in-0 data-open:zoom-in-95 data-closed:animate-out data-closed:fade-out-0 data-closed:zoom-out-95',
        $alignClass,
    ]) }}
>
    <x-select.scroll-up />

    <ul role="listbox" class="m-0 list-none p-0">
        {{ $slot }}
    </ul>

    <x-select.scroll-down />
</div>

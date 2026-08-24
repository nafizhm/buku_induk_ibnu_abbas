{{--
    <x-select.scroll-down> — setara <SelectScrollDownButton> (SelectPrimitive.ScrollDownArrow).
    Muncul otomatis saat konten bisa di-scroll ke bawah (state scrollDown di root).
--}}
@props([])

<button type="button" data-slot="select-scroll-down-button" x-show="scrollDown"
    @click="$refs.content.scrollBy({ top: 40, behavior: 'smooth' })"
    {{ $attributes->class([
        'sticky bottom-0 z-10 flex w-full cursor-default items-center justify-center bg-popover py-1',
        '[&_svg:not([class*=\'size-\'])]:size-4',
    ]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
        <path d="m6 9 6 6 6-6" />
    </svg>
</button>

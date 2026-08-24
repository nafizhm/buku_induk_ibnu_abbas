{{--
    <x-select.scroll-up> — setara <SelectScrollUpButton> (SelectPrimitive.ScrollUpArrow).
    Muncul otomatis saat konten bisa di-scroll ke atas (state scrollUp di root).
--}}
@props([])

<button type="button" data-slot="select-scroll-up-button" x-show="scrollUp"
    @click="$refs.content.scrollBy({ top: -40, behavior: 'smooth' })"
    {{ $attributes->class([
        'sticky top-0 z-10 flex w-full cursor-default items-center justify-center bg-popover py-1',
        '[&_svg:not([class*=\'size-\'])]:size-4',
    ]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
        <path d="m18 15-6-6-6 6" />
    </svg>
</button>

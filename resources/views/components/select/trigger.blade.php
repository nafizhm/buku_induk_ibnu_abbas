{{--
    <x-select.trigger> — setara <SelectTrigger> (SelectPrimitive.Trigger).

    Jika root `searchable` aktif, trigger berisi <input> untuk mengetik/memfilter
    (mode combobox ala shadcn). Jika tidak, menampilkan value via <span> seperti biasa.
    Ukuran diambil dari state root `size` (default | xs | sm | lg).

    State root (value, selectedLabel, size, searchable, query, active, open, dsb.)
    diakses langsungByName karena trigger berada dalam scope x-data root.
--}}
@props([])

<div @click="if (searchable) { if (!open) { open = true; query = ''; $nextTick(() => $refs.search && $refs.search.focus()) } } else { toggle() }"
    :disabled="disabled" :aria-expanded="open" :data-placeholder="!selectedLabel ? '' : null"
    :role="searchable ? 'combobox' : 'button'" tabindex="0"
    @keydown.enter.prevent="if (!searchable) toggle()"
    @keydown.space.prevent="if (!searchable) toggle()"
    aria-haspopup="listbox"
    data-slot="select-trigger"
    :data-size="size"
    x-bind:class="{
        'h-8 pr-2 pl-2.5 py-1 text-sm': size === 'default',
        'h-6 rounded-[min(var(--radius-md),8px)] pr-1.5 pl-2 py-0.5 text-xs': size === 'xs',
        'h-7 rounded-[min(var(--radius-md),10px)] pr-2 pl-2.5 py-0.5 text-sm': size === 'sm',
        'h-10 pr-3 pl-3 py-1.5 text-sm': size === 'lg',
        'opacity-50 cursor-not-allowed pointer-events-none': disabled,
    }"
    {{ $attributes->class([
        'flex w-full items-center justify-between gap-1.5 rounded-lg border border-input bg-transparent transition-colors outline-none select-none',
        'focus-visible:border-ring focus-visible:ring-3 focus-visible:ring-ring/50',
        'disabled:cursor-not-allowed disabled:opacity-50',
        'aria-invalid:border-destructive aria-invalid:ring-3 aria-invalid:ring-destructive/20',
        'data-[placeholder]:text-muted-foreground',
        'dark:bg-input/30 dark:hover:bg-input/50 dark:aria-invalid:border-destructive/50 dark:aria-invalid:ring-destructive/40',
        '[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
        '*:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex *:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-1.5',
    ]) }}>

    {{-- Mode biasa: tampilkan value --}}
    <span x-show="!searchable" class="flex flex-1 text-left" data-slot="select-value"
        x-text="selectedLabel || placeholder"></span>

    {{-- Mode searchable: input filter --}}
    <input x-show="searchable" type="text" x-ref="search" :readonly="!open"
        :value="searchable && open ? query : (selectedLabel || placeholder)"
        @click.stop="if (!open) { open = true; query = ''; $nextTick(() => $refs.search && $refs.search.focus()) }"
        @input="query = $event.target.value; active = null"
        @keydown.arrow-down.prevent.stop="moveActive(1)"
        @keydown.arrow-up.prevent.stop="moveActive(-1)"
        @keydown.enter.prevent.stop="if (active !== null) choose(active)"
        @keydown.escape.stop="close(); query = ''"
        aria-autocomplete="list" autocomplete="off"
        placeholder="Cari..."
        class="flex flex-1 bg-transparent text-left outline-none placeholder:text-muted-foreground"
        data-slot="select-value" />

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="pointer-events-none size-4 text-muted-foreground">
        <path d="M8 9l4-4 4 4" />
        <path d="M16 15l-4 4-4-4" />
    </svg>
</div>

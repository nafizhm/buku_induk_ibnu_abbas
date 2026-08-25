{{--
    <x-select.item value="draft" label="Draft">Draft</x-select.item>

    `label` dipakai untuk teks yang tampil di trigger setelah dipilih, dan sebagai
    sumber pencarian saat mode `searchable` aktif (difilter oleh `query` di root).
    `value` boleh string/number/bool.

    Catatan Alpine: nested component mewarisi scope parent, jadi state root
    (value, query, searchable, active, selectedLabel, items, select) diakses
    langsungByName (bukan $parent, yang tidak tersedia di dalam expression/getter).

    Satu elemen hanya boleh punya SATU binding class — ukuran & highlight aktif
    digabung ke dalam `x-bind:class` tunggal (Alpine membuang binding class kedua).
--}}
@props(['value', 'label' => null])

@php
    $search = strtolower((string) ($label ?? $value));
@endphp

<li
    x-init="items[@js($value)] = @js($label ?? $value); if (!multiple && value === @js($value)) _label = @js($label ?? $value)"
    @click="select(@js($value), @js($label ?? $value))"
    x-show="!(searchable && query) || '{{ $search }}'.includes(query.toLowerCase())"
    :aria-selected="multiple ? value.includes(@js($value)) : value === @js($value)"
    :data-disabled="disabled"
    :data-value="@js($value)"
    data-slot="select-item"
    :data-size="size"
    role="option"
    {{ $attributes->class([
        'relative flex w-full cursor-default items-center gap-1.5 rounded-md outline-hidden select-none',
        'hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground',
        'not-data-[variant=destructive]:focus:**:text-accent-foreground',
        'data-disabled:pointer-events-none data-disabled:opacity-50',
        '[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
        '*:[span]:last:flex *:[span]:last:items-center *:[span]:last:gap-2',
    ]) }}
    x-bind:class="{
        'py-1.5 pr-8 pl-1.5 text-sm': size === 'default',
        'py-1 pr-6 pl-1 text-xs': size === 'xs',
        'py-1 pr-7 pl-1.5 text-sm': size === 'sm',
        'py-2 pr-9 pl-2 text-sm': size === 'lg',
        'bg-accent text-accent-foreground': active == @js($value),
    }"
>
    <span class="flex flex-1 shrink-0 gap-2 whitespace-nowrap">
        {{ $slot }}
    </span>

    <span class="pointer-events-none absolute right-2 flex size-4 items-center justify-center"
        x-show="multiple ? value.includes(@js($value)) : value === @js($value)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
            <path d="M20 6L9 17l-5-5" />
        </svg>
    </span>
</li>

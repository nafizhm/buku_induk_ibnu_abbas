@props(['value'])

<button type="button" role="tab" data-slot="tabs-trigger"
    :data-active="active === @js($value) ? '' : null"
    :aria-selected="active === @js($value)" @click="active = @js($value)"
    {{ $attributes->merge([
        'class' => '
                relative
                inline-flex
                h-[calc(100%-1px)]
                flex-1
                items-center
                justify-center
                gap-1.5
                rounded-md
                border
                border-transparent
                font-medium
                whitespace-nowrap
                text-foreground/60
                transition-all
                hover:text-foreground
                focus-visible:border-ring
                focus-visible:ring-[3px]
                focus-visible:ring-ring/50
                focus-visible:outline-1
                focus-visible:outline-ring
                disabled:pointer-events-none
                disabled:opacity-50
                aria-disabled:pointer-events-none
                aria-disabled:opacity-50
                data-[active]:bg-background
                data-[active]:text-foreground
                dark:data-[active]:border-input
                dark:data-[active]:bg-input/30
                dark:data-[active]:text-foreground
                px-2.5
                text-sm
                [&_svg]:pointer-events-none
                [&_svg]:shrink-0
                [&_svg:not([class*="size-"])]:size-4
            ',
    ]) }}>
    {{ $slot }}
</button>

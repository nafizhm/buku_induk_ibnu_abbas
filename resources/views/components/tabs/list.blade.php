@props([
    'variant' => 'default',
    'size' => 'lg',
])

@php
    $baseClasses = '
        group/tabs-list
        inline-flex
        w-fit
        items-center
        justify-center
        rounded-lg
        p-[3px]
        text-muted-foreground
        group-data-vertical/tabs:h-fit
        group-data-vertical/tabs:flex-col
        data-[variant=line]:rounded-none
    ';

    $variantClasses = match ($variant) {
        'default' => 'bg-muted',

        'line' => 'gap-1 bg-transparent',

        default => throw new InvalidArgumentException("Invalid tabs variant [{$variant}]."),
    };

    $sizeClasses = match ($size) {
        'default' => 'h-8',

        'xs' => 'h-6',

        'sm' => 'h-7',

        'lg' => 'h-9',

        default => throw new InvalidArgumentException("Invalid tabs size [{$size}]."),
    };

    $classes = implode(' ', [$baseClasses, $variantClasses, $sizeClasses]);
@endphp

<div data-slot="tabs-list" data-variant="{{ $variant }}" data-size="{{ $size }}" role="tablist"
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

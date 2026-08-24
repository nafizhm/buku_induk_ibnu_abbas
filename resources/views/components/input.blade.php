@props([
    'size' => 'lg',
])

@php
    $baseClasses =
        'w-full min-w-0 rounded-lg border border-input bg-transparent transition-colors outline-none file:inline-flex file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-3 focus-visible:ring-ring/50 disabled:pointer-events-none disabled:cursor-not-allowed disabled:bg-input/50 disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-3 aria-invalid:ring-destructive/20 dark:bg-input/30 dark:disabled:bg-input/80 dark:aria-invalid:border-destructive/50 dark:aria-invalid:ring-destructive/40';

    $sizeClasses = match ($size) {
        'default' => 'h-8 px-2.5 py-1 text-base md:text-sm file:h-6 file:text-sm',

        'xs' => 'h-6 rounded-[min(var(--radius-md),8px)] px-2 py-0.5 text-xs file:h-5 file:text-[0.7rem]',

        'sm' => 'h-7 rounded-[min(var(--radius-md),10px)] px-2 py-0.5 text-sm file:h-6 file:text-xs',

        'lg' => 'h-10 px-3 py-1.5 text-sm file:h-7 file:text-sm',

        default => throw new InvalidArgumentException("Invalid input size [{$size}]."),
    };

    $classes = implode(' ', [$baseClasses, $sizeClasses]);
@endphp

<input data-slot="input" {{ $attributes->merge(['class' => $classes]) }}>

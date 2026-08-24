<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style type="text/tailwindcss">
        [x-cloak] {
            display: none;
        }

        @theme {
            --font-sans: "Outfit", sans-serif;

            --color-background: var(--background);
            --color-foreground: var(--foreground);

            --color-card: var(--card);
            --color-card-foreground: var(--card-foreground);

            --color-primary: var(--primary);
            --color-primary-foreground: var(--primary-foreground);

            --color-secondary: var(--secondary);
            --color-secondary-foreground: var(--secondary-foreground);

            --color-muted: var(--muted);
            --color-muted-foreground: var(--muted-foreground);

            --color-accent: var(--accent);
            --color-accent-foreground: var(--accent-foreground);

            --color-destructive: var(--destructive);

            --color-border: var(--border);
            --color-input: var(--input);
            --color-ring: var(--ring);

            --color-popover: var(--popover);
            --color-popover-foreground: var(--popover-foreground);

            --radius-sm: calc(var(--radius) * 0.6);
            --radius-md: calc(var(--radius) * 0.8);
            --radius-lg: var(--radius);
            --radius-xl: calc(var(--radius) * 1.4);
        }

        :root {
            --background: oklch(1 0 0);
            --foreground: oklch(0.147 0.004 49.3);

            --card: oklch(1 0 0);
            --card-foreground: oklch(0.147 0.004 49.3);

            --primary: oklch(0.841 0.238 128.85);
            --primary-foreground: oklch(0.405 0.101 131.063);

            --secondary: oklch(0.967 0.001 286.375);
            --secondary-foreground: oklch(0.21 0.006 285.885);

            --muted: oklch(0.96 0.002 17.2);
            --muted-foreground: oklch(0.547 0.021 43.1);

            --accent: oklch(0.841 0.238 128.85);
            --accent-foreground: oklch(0.405 0.101 131.063);

            --destructive: oklch(0.577 0.245 27.325);

            --border: oklch(0.922 0.005 34.3);
            --input: oklch(0.922 0.005 34.3);
            --ring: oklch(0.714 0.014 41.2);

            --popover: oklch(1 0 0);
            --popover-foreground: oklch(0.147 0.004 49.3);

            --radius: 0.625rem;
        }

        .dark {
            --background: oklch(0.147 0.004 49.3);
            --foreground: oklch(0.986 0.002 67.8);

            --card: oklch(0.214 0.009 43.1);
            --card-foreground: oklch(0.986 0.002 67.8);

            --primary: oklch(0.768 0.233 130.85);
            --primary-foreground: oklch(0.405 0.101 131.063);

            --secondary: oklch(0.274 0.006 286.033);
            --secondary-foreground: oklch(0.985 0 0);

            --border: oklch(1 0 0 / 10%);
            --input: oklch(1 0 0 / 15%);
            --ring: oklch(0.547 0.021 41.2);

            --popover: oklch(0.214 0.009 43.1);
            --popover-foreground: oklch(0.986 0.002 67.8);
        }
    </style>
</head>

<body class="bg-background text-foreground">
    {{ $slot ?? '' }}

    @yield('content')
</body>

</html>

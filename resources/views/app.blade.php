<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      @class(['dark'=> ($appearance ?? 'system') === 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ Detect system dark mode --}}
    <script>
        (function () {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    {{-- ✅ Facebook Pixel (Loaded from Database) --}}
    @if(!empty($page['props']['settings']['fb_pixel_id']))
        <script>
            !(function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            })(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');

            fbq('init', '{{ $page["props"]["settings"]["fb_pixel_id"] }}');
            fbq('track', 'PageView');
        </script>
    @endif

    {{-- ✅ Custom Scrollbar & Theme --}}
    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }

        body {
            --sb-track-color: transparent;
            --sb-thumb-color: oklch(55.1% 0.027 264.364);
            --sb-size: 4px;
        }

        ::-webkit-scrollbar {
            width: var(--sb-size)
        }

        ::-webkit-scrollbar-track {
            background: var(--sb-track-color);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--sb-thumb-color);
            border-radius: 3px;
        }

        @supports not selector(::-webkit-scrollbar) {
            body {
                scrollbar-color: var(--sb-thumb-color) var(--sb-track-color);
            }
        }
    </style>

    <title inertia>{{ config('app.name', 'Bikroyon') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    {{-- Vite + Inertia --}}
    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="base_url" content="{{ url('/') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        @stack('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $(function () {
                    // $('[data-toggle="tooltip"]').tooltip()
                })
                /*Advance pos variables*/
                var site = {
                    base_url: BASE_URL,
                    settings: {
                        default_warehouse: 1,
                        product_serial: 0,
                        default_currency: 'USD',
                        product_discount: 1,
                        overselling: 0,
                        tax1: 1,
                        display_symbol: null,
                        symbol: null,
                        set_focus: 0,
                        decimals: 2,
                        qty_decimals: 2,
                        thousands_sep: ',',
                        decimals_sep: '.',
                        sac: 0,
                        order_tax_rate: false,
                        products_page: 0
                    }
                }
                // console.log(BASE_URL)
                // console.log($('meta[name=base_url]').prop('content'))
            });
        </script>
    </body>
</html>

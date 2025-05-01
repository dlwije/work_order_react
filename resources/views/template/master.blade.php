<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | {{ config('app.name', 'Day&NightRV') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('/') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @stack('css')

    <style>.content-header{ padding: 5px .5rem;}</style>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper" id="example">
    <!-- Navbar -->
    @include('template.navbar')
    <!-- /.navbar -->
    <div class="col-sm-12" id="loadingDiv" style="display: none;">
        <div class="cssload-preloader cssload-loading">
            <span class="cssload-slice"></span>
            <span class="cssload-slice"></span>
            <span class="cssload-slice"></span>
            <span class="cssload-slice"></span>
            <span class="cssload-slice"></span>
            <span class="cssload-slice"></span>
        </div>
    </div>
    <!-- Main Sidebar Container -->
    @include('template.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('content_header')
        </section>
        <!-- Content Header (Page header) end -->

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            @yield('content')
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('template.footer')
</div>
<!-- ./wrapper -->

<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/custom.js') }}"></script>
@stack('scripts')
<script>
    $(function () { $('[data-toggle="tooltip"]').tooltip() })
    /*Advance pos variables*/
    var site = {base_url: BASE_URL,settings:{ default_warehouse: 1, product_serial: 0, default_currency: 'USD', product_discount: 1, overselling: 0, tax1: 1, display_symbol: null, symbol: null, set_focus: 0, decimals: 2, qty_decimals: 2, thousands_sep: ',', decimals_sep: '.', sac: 0, order_tax_rate: false, products_page: 0}}
</script>
</body>
</html>

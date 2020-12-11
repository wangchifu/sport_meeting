<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')-{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" crossorigin="anonymous" />
    <script src="{{ asset('js/all.min.js') }}" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
@include('layouts.navbar')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            @include('layouts.side')
            @include('layouts.footer')
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            @yield('main')
        </main>
        @include('layouts.footer2')
    </div>
</div>
<script src="{{ asset('js/jquery-3.5.1.slim.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/chart-bar-demo.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables-demo.js') }}/"></script>
</body>
</html>

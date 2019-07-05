<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('plugins/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('plugins/adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{ asset('plugins/adminlte/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css?t=') . time() }}">
    <link rel="stylesheet" href="{{ asset('plugins/nprogress/nprogress.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/nestable/nestable.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/build/toastr.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

{{--<body class="hold-transition skin-blue sidebar-mini">--}}
<body class="skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">

    @include('admin.partials.header')

    @include('admin.partials.sidebar')

    <div class="content-wrapper" id="pjax-container">
        <div id="app">
            @yield('content')
        </div>
    </div>

    @include('admin.partials.footer')

</div>
<!-- ./wrapper -->

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";
</script>

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-pjax/jquery.pjax.js') }}"></script>
<script src="{{ asset('plugins/nprogress/nprogress.js') }}"></script>
<script src="{{ asset('plugins/nestable/jquery.nestable.js') }}"></script>
<script src="{{ asset('plugins/toastr/build/toastr.min.js') }}"></script>
<script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js') }}"></script>
<script src="{{ asset('js/app.js?t=') . time() }}"></script>
@yield('footer')
</body>
</html>


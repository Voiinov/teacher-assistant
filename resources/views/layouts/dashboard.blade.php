<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(trim($__env->yieldContent('pageTitle')))@yield("pageTitle") | {{ config('app.name', 'TeA') }}@else{{ config('app.name', 'TeA') }}@endif</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}" rel="stylesheet">
    <!-- Toastr -->
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @yield("CSS")
    @stack('css')

</head>

<body class="hold-transition sidebar-mini">

    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include("layouts.nav.sidebar")
       <!-- /.navbar -->

       <!-- Main Sidebar Container -->
       @include("layouts.nav.topbar")
        <!--  Content Wrapper. Contains page content  -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div class="content-wrapper">
            <!-- page content -->
                <section class="content-header">
                    <div class="container-fluid">
                        @yield("content-header")
                    </div><!-- /.container-fluid -->
                </section>
                <section class="content">
                    @foreach (['success'=>'success', 'errors'=>'danger', 'warning'=>'warning', 'info'=>'warning'] as $type=>$class)
                        @if(session($type))
                            @foreach(session($type)->all() as $alert)
                                <x-alert :$class :$type>
                                    {{ __($alert) }}
                                </x-alert>
                            @endforeach
                        @endif
                    @endforeach
                    @yield("content")
                </section>
            </div>
            <!-- End of Main Content -->

        </div>
        @stack('modals')
            <!-- Footer -->
            <footer class="main-footer">
                <div class="float-right d-none d-sm-block">
                  <b>Version</b> {{ config('app.version') }}
                </div>
                <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
              </footer>
            <!-- End of Footer -->

        <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> --}}
<!-- Bootstrap -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}
<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
{{-- <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script> --}}
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
{{-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script> --}}
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $( ".ui-sortable" ).sortable();
    });
</script>
@yield("JS")
@stack('js')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard</title>
    user2-160x160.jpg
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("plugins/fontawesome-free/css/all.min.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/adminlte.min.css") }}">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @yield('style')

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


    <x-navbar></x-navbar>
    <!-- Main Sidebar Container -->
    <x-sidebar></x-sidebar>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        @yield('content')
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>

@include('sweetalert::alert')

<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset("plugins/jquery/jquery.min.js") }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset("plugins/jquery-ui/jquery-ui.min.js") }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset("plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>

<!-- AdminLTE for demo purposes -->
@yield('script')




</body>
</html>

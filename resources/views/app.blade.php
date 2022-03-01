<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laravel Firebase</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 5.0 -->
    <link rel="stylesheet" href="{{ url('assets') }}/bootstrap/css/bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('assets') }}/DataTables/css/datatables.min.css">
    <!-- DataTables Bootstrap 5.0 -->
    <link rel="stylesheet" href="{{ url('assets') }}/DataTables/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert 2 -->
    <link rel="stylesheet" href="{{ url('assets') }}/sweetalert2/sweetalert2.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ url('assets') }}/toastr/toastr.min.css">
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Barang</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @if(!Auth::user())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.login') }}">Login</a>
                        </li>
                        @endif
                        @if(Auth::user())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.logout') }}">Logout</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('barang.index') }}">Barang</a>
                        </li>
                        @if(Auth::user())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('log_barang.index') }}">Log Barang</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Content -->
    <div class="container-fluid">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ url('assets') }}/bootstrap/js/bootstrap.min.js"></script>
    <!-- jQuery-->
    <script src="{{ url('assets') }}/jquery/jquery.min.js"></script>
    <!-- DataTables JS -->
    <script src="{{ url('assets') }}/DataTables/js/datatables.min.js"></script>
    <!-- DataTables BV5 JS -->
    <script src="{{ url('assets') }}/DataTables/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert 2 -->
    <script src="{{ url('assets') }}/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="{{ url('assets') }}/toastr/toastr.min.js"></script>
    <!-- Main JS-->
    <script src="{{ url('assets') }}/main.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('script')

</body>

</html>
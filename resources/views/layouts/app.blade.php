<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">



    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="left: inherit; right: 0px;">
                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                        <i class="mr-2 fas fa-file"></i>
                        {{ __('My profile') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item"
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="mr-2 fas fa-sign-out-alt"></i>
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        @include('layouts.navigation')
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->


<!-- DataTables -->
{{--<script src="/DataTables/datatables.js"></script>--}}
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>



<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}" defer></script>

<!-- Ваши собственные скрипты -->




<script>
    $(document).ready(function() {
        new DataTable('#positions', {
            order: [[1, 'desc']]
        });
    });
</script>

<script>
    $(document).ready(function(){
        // $('#empTable').DataTable({
        new DataTable('#empTable', {
            order: [[1, 'asc']],
            processing: true,
            serverSide: true,
            ajax: "{{ route('getEmployees') }}",
            columns: [
                {data: 'photo' },
                { data: 'name' },
                { data: 'position' },
                { data: 'date_of_employment' },
                { data: 'phone_number' },
                { data: 'email' },
                { data: 'salary' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<a href="{{ url('/employees') }}/' + row.id + '/edit" class="btn btn-sm btn-light">' +
                            '<span class="d-flex align-items-center">' +
                            '<i class="far fa-edit mr-1"></i></span></a>' +
                            '<form id="deleteForm-' + row.id + '" action="{{ url('/employees') }}/' + row.id + '" method="POST" style="display: inline;">' +
                            '@csrf' +
                            '@method("DELETE")' +
                            '<button type="submit" class="btn btn-sm btn-light" onclick="return confirm(\'Вы уверены?\')">' +
                            '<span class="d-flex align-items-center">' +
                            '<i class="far fa-trash-alt mr-1"></i></span></button></form>';
                    },
                    name: 'action',
                    orderable: false

                }
            ]
        });
    });
</script>




@vite('resources/js/app.js')

<!-- Ваши собственные скрипты -->
@yield('scripts')

<script>
    $(document).ready(function() {
        $('.select-manager').select2();

        var inputElement = $('input[name="name"]');
        var charCount = $('#charCount');

        if (inputElement.length) {
            // Учтем начальную длину текста
            charCount.text(inputElement.val().length + '/256');

            inputElement.on('input', function () {
                charCount.text($(this).val().length + '/256');
            });
        }
    });
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
</html>

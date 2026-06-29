<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ __('Lecture') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" />
    <!-- flag-icon-css -->
    <link rel="stylesheet" href="{{ asset('/plugins/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <!-- Tempusdominus Bootstrap 4 -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" /> --}}
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" />
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" />
    <!-- Daterange picker -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" /> --}}
    <!-- summernote -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}" /> --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">
    <!-- jQuery -->
    <script src="{{ asset('https://code.jquery.com/jquery-3.5.1.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('https://code.jquery.com/jquery-3.5.1.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- chọn nhiều trong multiselect -->
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
  <style type="text/css">
        .dropdown-toggle{
            height: 50px;
            width:760px !important;
        }
    </style> 
    <!-- thử cái này -->
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                               <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ asset('/covan') }}" class="nav-link">{{ __('Home') }}</a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Language Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        @if (Session::has('language') && Session::get('language') == 'vi')
                            <i class="flag-icon flag-icon-vn"></i>
                        @else
                            <i class="flag-icon flag-icon-us"></i>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        @if (Session::has('language') && Session::get('language') == 'vi')
                            <a href="{{ asset('/language/en') }}" class="dropdown-item ">
                                <i class="flag-icon flag-icon-us mr-2"></i> {{ __('English') }}
                            </a>
                            <a href="{{ asset('/language/vi') }}" class="dropdown-item active">
                                <i class="flag-icon flag-icon-vn mr-2"></i> {{ __('Vietnamese') }}
                            </a>
                        @else
                            <a href="{{ asset('/language/en') }}" class="dropdown-item active">
                                <i class="flag-icon flag-icon-us mr-2"></i> {{ __('English') }}
                            </a>
                            <a href="{{ asset('/language/vi') }}" class="dropdown-item">
                                <i class="flag-icon flag-icon-vn mr-2"></i> {{ __('Vietnamese') }}
                            </a>
                        @endif
                    </div>
                </li>
                <!-- Messages Dropdown Menu -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="{{ asset('/dang-xuat') }}" role="button">
                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.html" class="brand-link">
                <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: 0.8" />
                <span class="brand-text font-weight-light">Cố vấn</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('dist/img/avatar3.png') }}" class="img-circle elevation-2"
                            alt="User Image" />
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Session::get('hoGV') }} {{ Session::get('tenGV') }}</a>
                    </div>
                </div>
                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Tìm kiếm...."
                            aria-label="Search" />
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ asset('/covan') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Bảng thông tin tổng hợp</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                <a href="{{ asset('/giang-vien/de-danh-gia') }}" class="nav-link">
                  <i class="nav-icon fas fa-store-alt"></i>
                  <p>Đề đánh giá</p>
                </a>
              </li> --}}
                       
                        {{-- <li class="nav-item">
                            <a href="{{ asset('/giang-vien/cham-diem-bao-cao') }}" class="nav-link">
                                <i class="fas fa-balance-scale-left"></i>
                                <p>{{ __('Instructor') }}</p>
                            </a>
                        </li> --}}

                        
                        {{-- <li class="nav-item">
                            <a href="{{ asset('/giang-vien/phan-bien-de-cuong') }}" class="nav-link">
                                <i class="fas fa-business-time"></i>
                                <p>{{ __('revise syllabus') }}</p>
                            </a>
                        </li> --}}

                        {{-- <li class="nav-item">
                            <a href="{{ asset('/giang-vien/tai-khoan') }}" class="nav-link">
                                <i class="fas fa-user-cog"></i>
                            <p>{{ __('Account') }}</p>
                            </a>
                        </li> --}}
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
            
        </aside>
        @include('sweet::alert')


        @yield('content')
        <footer class="main-footer">
            <strong>Copyright &copy; 
                {{-- <a href=""> {{ __('C.A.P System') }}</a>. --}}
                <a href="{{ asset('/giang-vien') }}"> CAP</a>.</strong>
            </strong>
            <div class="float-right d-none d-sm-inline-block">
                {{-- <b>Version</b> 1.0 --}}
            </div>
        </footer>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <script>
        $.widget.bridge("uibutton", $.ui.button);

  
    </script>


    <script type="text/javascript" src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(function() {
            $("#example2").DataTable({
                "responsive": true,
                "autoWidth": false,
                "aLengthMenu": [[30, 40, 50], [ 30, 40, 50]]
            });
            $('.select2').select2();
        });

    </script>
    @yield('scripts')
</body>

</html>

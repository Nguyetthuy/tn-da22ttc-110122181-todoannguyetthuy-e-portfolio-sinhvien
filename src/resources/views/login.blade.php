<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ __('Login') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}" />
</head>


<body class="hold-transition login-page" style="background: #ebeced47">
   
    <div style="text-align:center;"> 
        <b> 
            <img src="{{ asset('dist/img/logo_trang_chu.png') }}" height="150pt" >
            <h2  style="font-weight: 800; line-height: 1.5;">HỆ THỐNG HỖ TRỢ CÔNG TÁC ĐÁNH GIÁ THEO CHUẨN ĐẦU RA 
                
            <br> CỦA CHƯƠNG TRÌNH ĐÀO TẠO  PHỤC VỤ CẢI TIẾN, NÂNG CAO <br> CHẤT LƯỢNG ĐÀO TẠO</h2> </b>
       <i>  <h2 style="line-height: 1.5;">(Learning outcomes evaluation system <br>
       For  curriculum to support improvement and advanced training quality)</h2> </i>
    </div>
    <form action="{{ asset('/dang-nhap') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="login-box">
            <div class="login-logo">
                {{-- <a href="#"><b>{{ __('Login') }}</b></a> --}}
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg"></p>
                    <div>
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!--<div class="col-7">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember" />
                                    <label for="remember"> Remember </label>
                                </div>
                            </div>-->
                            <!-- /.col -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Login') }}
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
            </div>
    </form>
    <!-- /.login-box -->
    <!-- jQuery -->
    <script src="{{ asset('https://code.jquery.com/jquery-3.5.1.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('https://code.jquery.com/jquery-3.5.1.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>

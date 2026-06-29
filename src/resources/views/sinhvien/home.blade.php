@extends('sinhvien.master')
@section('content')
<div class="content-wrapper" style="min-height: 31px;">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark font-weight-bold">
            <i class="fas fa-tachometer-alt mr-2 text-primary"></i>Dashboard Sinh Viên
          </h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ asset('sinh-vien/') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
      <!-- Card Thông Tin Tổng Quan Sinh Viên -->
      <div class="card card-widget widget-user shadow-lg mb-4" style="border-radius: 15px; overflow: hidden; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="widget-user-header text-white p-4" style="background: transparent;">
          <h3 class="widget-user-username font-weight-bold" style="font-size: 1.8rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
            {{ $svInfo->HoSV }} {{ $svInfo->TenSV }}
          </h3>
          <h5 class="widget-user-desc" style="opacity: 0.9; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            MSSV: <b>{{ $svInfo->maSSV }}</b>
          </h5>
        </div>
        <div class="card-footer bg-white p-0">
          <div class="row p-4">
            <div class="col-md-6 border-right">
              <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3">
                <i class="fas fa-user-circle mr-2"></i> Thông Tin Cá Nhân
              </h5>
              <table class="table table-borderless table-sm">
                <tr>
                  <td style="width: 35%;" class="text-muted">Giới tính:</td>
                  <td class="font-weight-bold">{{ $svInfo->Phai ?? 'Chưa rõ' }}</td>
                </tr>
                <tr>
                  <td class="text-muted">Ngày sinh:</td>
                  <td class="font-weight-bold">{{ $svInfo->NgaySinh ?? 'Chưa rõ' }}</td>
                </tr>
                <tr>
                  <td class="text-muted">Khóa học:</td>
                  <td class="font-weight-bold">Khóa {{ $svInfo->namTS ?? 'Chưa rõ' }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3">
                <i class="fas fa-graduation-cap mr-2"></i> Thông Tin Đào Tạo
              </h5>
              <table class="table table-borderless table-sm">
                <tr>
                  <td style="width: 35%;" class="text-muted">Lớp học:</td>
                  <td class="font-weight-bold text-danger">{{ $svInfo->tenLop }} <span class="text-muted">({{ $svInfo->maLop }})</span></td>
                </tr>
                <tr>
                  <td class="text-muted">Chương trình:</td>
                  <td class="font-weight-bold">{{ $svInfo->tenCT ?? 'Chưa rõ' }}</td>
                </tr>
                <tr>
                  <td class="text-muted">Cố vấn học tập:</td>
                  <td class="font-weight-bold text-info"><i class="fas fa-user-shield mr-1"></i> {{ $tenCoVan }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Hộp Chỉ Số Thống Kê Học Tập -->
      <div class="row">
        <!-- ------------cột 1--------------------- -->
        <div class="col-lg-4 col-6 mb-4">
          <div class="small-box bg-info elevation-2" style="border-radius: 12px; overflow: hidden; height: 100%;">
            <div class="inner">
              <h3>{{ $countHocPhan }}</h3>
              <p class="font-weight-bold">Môn học đã tham gia</p>
            </div>
            <div class="icon">
              <i class="fas fa-book"></i>
            </div>
            <div class="small-box-footer py-2 text-center" style="background: rgba(0,0,0,0.1); font-size: 0.9rem;">
              Tổng số môn học tham gia
            </div>
          </div>
        </div>
        <!-- ---------------------------------------->
        <!-- ----------------cột 2------------------ -->
        <div class="col-lg-4 col-6 mb-4">
          <div class="small-box bg-success elevation-2" style="border-radius: 12px; overflow: hidden; height: 100%;">
            <div class="inner">
              <h3>{{ $countHocKy }}</h3>
              <p class="font-weight-bold">Học kỳ học tập</p>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="small-box-footer py-2 text-center" style="background: rgba(0,0,0,0.1); font-size: 0.9rem;">
              Tổng số học kỳ tích lũy
            </div>
          </div>
        </div>
        <!------------------------------------------- -->
        <!-- --------------------------cột 3---------------- -->
        <div class="col-lg-4 col-6 mb-4">
          <div class="small-box bg-warning elevation-2" style="border-radius: 12px; overflow: hidden; height: 100%;">
            <div class="inner">
              <h3 class="text-white">{{ $countPLODat }} / {{ $countPLO }}</h3>
              <p class="font-weight-bold text-white">Chuẩn đầu ra CTĐT (PLO) đạt</p>
            </div>
            <div class="icon">
              <i class="fas fa-chart-line text-white-50"></i>
            </div>
            <div class="small-box-footer py-2 text-white text-center" style="background: rgba(0,0,0,0.15); font-size: 0.9rem;">
              Tỷ lệ đạt chuẩn đầu ra chương trình
            </div>
          </div>
        </div>
        <!--------------------------------------------------- -->
        
      </div>
      
    </div>
  </section>
</div>
@endsection
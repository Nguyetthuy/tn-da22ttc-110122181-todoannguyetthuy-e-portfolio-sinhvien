@extends('sinhvien.khaosatmaster')
@section('content')
<div class="content-wrapper" style="min-height: 155px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">
              Danh sách sinh viên<noscript></noscript>
              <nav></nav>
            </h1>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
              <li class="breadcrumb-item">
                <a href="{{ asset('giang-vien/hoc-phan') }}">Học phần</a>
              </li>
              <li class="breadcrumb-item active">Danh sách sinh viên</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                    

                
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th>STT</th>
                          <th>Mã sinh viên</th>
                          <th>Tên sinh viên</th>
                          <th>Lớp</th>
                      </tr>
                  </thead>
                  <tbody>
                    @php
                        $i=1;
                    @endphp
                 
                      @foreach ($dssv as $sv)
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$sv->maSSV}}</td>
                      <td>{{$sv->HoSV}} {{$sv->TenSV}}</td>
                        <td>{{$sv->maLop}}</td>
                      </tr>
                      @endforeach
                     
                  </tbody>
                  <tfoot></tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection
@extends('sinhvien.master')
@section('content')
<div class="content-wrapper" style="min-height: 58px;">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">
                Học phần<noscript></noscript>
            <nav></nav>
          </h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
            <li class="breadcrumb-item ">Học phần giảng dạy</li>
            <li class="breadcrumb-item active">Đáp ứng chuẩn đầu ra/li>
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
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#themCDR3">
                        Thêm chuẩn đầu ra 3
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="themCDR3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <form action="{{ asset('/giang-vien/hoc-phan/them-chuan-dau-ra') }}" method="post">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Thêm chuẩn đầu ra 3</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>  
                                <div class="modal-body">
                                    <select name="maCDR3" id="" class="form-control">
                                        @foreach ($cdr3 as $x)
                                            <option value="{{$x->maCDR3}}">{{$x->maCDR3VB}}: {{$x->tenCDR3}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </form>
                        
                        </div>
                    </div>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th >STT</th>
                    <th>Mã chuẩn đầu ra</th>
                    <th >Tên chuẩn đầu ra</th>
                    <th >Tùy chọn</th></tr>

                </thead>
                <tbody>
                @php
                    $i=1;
                @endphp
                @foreach ($chuandaura as $cdr)
                    <tr >
                    <td>{{$i++}}</td>
                    <td>
                        {{$cdr->maCDR3VB}}
                    </td>
                    
                   
                    <td>
                        {{$cdr->tenCDR3}}
                    </td>
                    <td>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-edit"></i>
                            Chỉnh sửa
                        </button>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ asset('/giang-vien/hoc-phan/sua-chuan-dau-ra') }}" method="post">
                                    @csrf
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Sửa chuẩn đầu ra</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="maCDR3_old" value="{{$cdr->maCDR3}}" hidden>
                                        <select name="maCDR3" id="" class="form-control">
                                            @foreach ($cdr3 as $x)
                                                <option value="{{$x->maCDR3}}">{{$x->maCDR3VB}}: {{$x->tenCDR3}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submtit" class="btn btn-primary">Lưu</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button> 
                                    </div>
                                </div>
                                </form>
                                
                            </div>
                        </div>
                    </td>
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
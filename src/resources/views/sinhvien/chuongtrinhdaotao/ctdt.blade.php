@extends('sinhvien.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Chương trình đào tạo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ asset('/sinh-vien') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">CTĐT</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section c class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ asset('/sinh-vien') }}">Trang chủ</a></li>
              <li class="breadcrumb-item active">Khảo sát CTĐT</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fas fa-check"></i> Thông báo!</h5>
          {{session('success')}}
        </div>
      @endif
      @if(session('warning'))
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fas fa-exclamation-triangle"></i> Thông báo!</h5>
          {{session('warning')}}
        </div>
      @endif
    <!-- Main content -->
    <input type="text" id="pks_cdr" value="{{ $pks_cdr }}" hidden>
    <input type="text" id="pks_cabet" value="{{ $pks_cabet }}" hidden>
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
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                     
                    </div><table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>STT</th>
                      <th>Mã lớp</th>
                      <th>Tên lớp </th>
                      <th>Năm học khảo sát</th>
                      <th>Học kì khảo sát</th>
                      <th>Loại khảo sát</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                        $i=1;
                    @endphp
                    @foreach ($giangday as $data)
                      <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $data->maLop }}</td>
                        <td>{{ $data->tenLop }}</td>
                        <td>{{ $data->namHoc }}</td>
                        <td>{{ $data->maHK }}</td>
                        <td>
                            <a id="status2" href="" class="btn btn-success" data-status="true" >
                                Khảo sát chuẩn đầu ra 3 
                            </a>
                            <a id="status3" href="" class="btn btn-info"  data-status="true" >
                                Khảo sát chuẩn abet 
                            </a> 
                        </td>
                      </tr>
                      @endforeach
               
                  </tbody>
                  <tfoot></tfoot>
                </table>
                <script>
                 
///////////////////////////////////////////////////////////////cdr//////////////////////////
                    $(document).ready(function() {

                  $('#status2').click(function(e) { //khi bam gui

                      e.preventDefault();
                      $.ajaxSetup({
                          headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          }
                      });
                      $('#status2').html('Khảo sát..');
                      console.log('Hello');
                      ////kiem tra da check
                    
                      var $pks_cdr = $("#pks_cdr").val(); //lay mang gia tri kqhht tu input text
                      console.log($pks_cdr);
                      var data = $.parseJSON($pks_cdr); //chuyen tu mang string sang json
                      console.log(data);

                      // if($pks_kqht = "[]"){
                      //   var href = $('#status1').attr('href');
                      // }
                      
                      let demFalse = 0;
                      $.each(data, function(i, v) //chay tung phan tu trong mang json
                          {
                              //kiem tra radio button da duoc chon 
                              if ( data!=[]) {
                                  demFalse += 1; 
                                  console.log(demFalse);
                              }
                          });

                          if (demFalse > 0) {
                              alert('Bạn đã khảo sát rồi');
                              $('#status2').html('Đã Khảo sát');
                              $("#status2").removeAttr('href');
                              e.preventDefault();
                              $(this).off("click").attr('a', "javascript: void(0);");
                              return false;
                          } else {
                            //var href = $('#status1').attr('href');
                            location.href = "{{ asset('sinh-vien/khao-sat-ctdt/khao-sat-cdr3/'.$data->maLop.'/'.$data->maSSV) }}" 
                          }
                    });
                  //-----end document
                  });
                    /////////////////////////////////////chuan abet///////////////////////////////////
                    $(document).ready(function() {

                        $('#status3').click(function(e) { //khi bam gui

                            e.preventDefault();
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $('#status3').html('Khảo sát..');
                            console.log('Hello');
                            ////kiem tra da check
                          
                            var $pks_cabet = $("#pks_cabet").val(); //lay mang gia tri kqhht tu input text
                            console.log($pks_cabet);
                            var data = $.parseJSON($pks_cabet); //chuyen tu mang string sang json
                            console.log(data);

                            // if($pks_kqht = "[]"){
                            //   var href = $('#status1').attr('href');
                            // }
                            
                            let demFalse = 0;
                            $.each(data, function(i, v) //chay tung phan tu trong mang json
                                {
                                    //kiem tra radio button da duoc chon 
                                    if ( data!=[]) {
                                        demFalse += 1; 
                                        console.log(demFalse);
                                    }
                                });

                                if (demFalse > 0) {
                                    alert('Bạn đã khảo sát rồi');
                                    $('#status3').html('Đã Khảo sát');
                                    $("#status3").removeAttr('href');
                                    e.preventDefault();
                                    $(this).off("click").attr('a', "javascript: void(0);");
                                    return false;
                                } else {
                                  //var href = $('#status1').attr('href');
                                  location.href = "{{ asset('sinh-vien/khao-sat-ctdt/khao-sat-chuanabet/'.$data->maLop.'/'.$data->maSSV) }}" 
                                }
                          });
                        //-----end document
                        });
                </script>
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
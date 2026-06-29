@extends('covan.master')

@section('content')
<style>
  /* Thêm CSS cho giao diện thêm sinh động, sang trọng */
  .year-card-header {
      background: linear-gradient(45deg, #17a2b8, #138496); /* Màu xanh ngọc (Info) của AdminLTE */
      color: white;
      font-weight: bold;
      cursor: pointer;
  }
  .table-hover tbody tr:hover {
      background-color: #f1f3f5;
  }
  .badge-credits {
      font-size: 0.9em;
      padding: 0.4em 0.6em;
  }
</style>

<div class="content-wrapper" style="min-height: 31px;">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ asset('sinh-vien/') }}">Home</a></li>
            <li class="breadcrumb-item active">Danh sách học phần</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          
          <h3 class="mb-4">Danh sách Học phần đã học</h3>
          
          <!-- Lặp qua từng Năm Học -->
          <div id="accordionYear">
            @forelse($groupedByYear as $namHoc => $semesters)
              <div class="card mb-4 shadow-sm">
                <!-- Header của Năm học -->
                <div class="card-header year-card-header" id="headingYear_{{ Str::slug($namHoc) }}" data-toggle="collapse" data-target="#collapseYear_{{ Str::slug($namHoc) }}" aria-expanded="true" aria-controls="collapseYear_{{ Str::slug($namHoc) }}">
                  <h5 class="mb-0">
                    <i class="fas fa-calendar-alt mr-2"></i> Năm học: {{ $namHoc }}
                    
                  </h5>
                </div>

                <div id="collapseYear_{{ Str::slug($namHoc) }}" class="collapse show" aria-labelledby="headingYear_{{ Str::slug($namHoc) }}" data-parent="#accordionYear">
                  <div class="card-body p-0">
                    
                    @foreach($semesters as $hocKy => $semesterData)
                    <!-- Tiêu đề Học kỳ & Hiển thị Điểm TB -->
          <div class="p-3 bg-light border-bottom">
              <strong class="text-info"><i class="fas fa-book-open mr-1"></i> {{ $hocKy }}</strong>
              <span class="badge badge-warning float-right ml-2 text-dark" style="font-size: 0.9em;">
                  Điểm TB Học kỳ: {{ $semesterData->diemTB }}
              </span>
              <span class="badge badge-secondary float-right">{{ count($semesterData->courses) }} học phần</span>
          </div>

                    <!-- Bảng danh sách học phần -->
                    <div class="table-responsive">
                      <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th style="width: 5%" class="text-center">STT</th>
                            <th style="width: 15%">Mã HP</th>
                            <th style="width: 45%">Tên học phần</th>
                            <th style="width: 15%">Mã Lớp</th>
                            <th style="width: 15%" class="text-center">Số tín chỉ</th>
                            <th style="width: 25%" class="text-center">Điểm học phần</th>
                          </tr>
                        </thead>
                         <tbody>
                          @foreach($semesterData->courses as $index => $course)
                            <tr>
                              <td class="text-center">{{ $index + 1 }}</td>
                              <td><strong>{{ $course->maHocPhan }}</strong></td>
                              <td>{{ $course->tenHocPhan }}</td>
                              
                              <!-- Thêm cột Mã Lớp -->
                              <td class="text-center">{{ $course->maLop }}</td> 
                              
                              <td class="text-center">
                                <span class="badge badge-success badge-credits">{{ $course->tongSoTinChi }}</span>
                              </td>
                              <td class="text-center text-danger font-weight-bold">
                                {{ $course->diem ?? '---' }}
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                    @endforeach
                    <!-- Hết bảng danh sách học phần -->
                  </div>
                </div>
              </div>
            @empty
              <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i> Hệ thống chưa ghi nhận học phần nào bạn đã học.
              </div>
            @endforelse
          </div>
          <!-- Hết lặp Học Kỳ -->

          <h3 class="mb-4 mt-5">Theo dõi học lực</h3>
          <!-- Thư viện Chart.js -->
          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <div class="row">
            <div class="col-md-12">
                <!-- Khung chứa Biểu đồ Học lực -->
                <div class="card mb-12 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line mr-2"></i> Biểu đồ học lực qua các học kỳ</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="gradeChart" height="400"></canvas>
                    </div>
                </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>

<!-- Khởi tạo Biểu đồ bằng Javascript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Biểu đồ học lực (Line Chart)
    const ctxGrade = document.getElementById('gradeChart').getContext('2d');
    const labelsGrade = {!! json_encode($chartLable ?? []) !!};
    const dataGrade = {!! json_encode($chartData ?? []) !!};
    
    new Chart(ctxGrade, {
        type: 'line',
        data: {
            labels: labelsGrade,
            datasets: [{
                label: 'Điểm trung bình',
                data: dataGrade,
                backgroundColor: 'rgba(23, 162, 184, 0.2)', // Xanh nhạt
                borderColor: 'rgba(23, 162, 184, 1)',      // Xanh ngọc Info
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(23, 162, 184, 1)',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    title: { display: true, text: 'Thang điểm' }
                },
                x: {
                    title: { display: true, text: 'Học kỳ - Năm học' }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

   
});
</script>

@endsection

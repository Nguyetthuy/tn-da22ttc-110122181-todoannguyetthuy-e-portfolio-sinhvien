@extends('covan.master')
@section('content')
    <div class="content-wrapper" style="min-height: 155px;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Xem thống kê theo học kì</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ asset('covan') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active">Phân công giảng dạy học phần</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title" style="font-size: 1.2rem;">
                            <i class="fas fa-graduation-cap mr-2 text-primary"></i> 
                            Danh sách học phần thuộc lớp hành chính: <b class="text-danger">{{ $maLop }}</b>
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        @if(count($giangday) > 0)
                            @php
                                // Chuyển mảng thành Collection của Laravel để áp dụng gom nhóm đa cấp
                                $collectionGD = collect($giangday)->map(function($item) {
                                    $mangHocKy = explode('-', $item->maHKNH);
                                    $item->tenHK = $mangHocKy[0] ?? $item->maHKNH;
                                    $item->namHoc = (isset($mangHocKy[1]) && isset($mangHocKy[2])) ? $mangHocKy[1] . '-' . $mangHocKy[2] : 'Chưa rõ';
                                    return $item;
                                })->groupBy(['namHoc', 'tenHK']); // Gom nhóm theo Năm học -> Học kỳ
                            @endphp

                            {{-- Duyệt qua từng Năm Học --}}
                            @foreach ($collectionGD as $namHoc => $cacHocKy)
                                <div class="nam-hoc-container mb-4 shadow-sm p-3 bg-white rounded border border-secondary">
                                    <h4 class="text-primary border-bottom pb-2 mb-3" style="font-weight: bold;">
                                        <i class="far fa-calendar-alt mr-2"></i> Năm học: {{ $namHoc }}
                                    </h4>

                                    {{-- Duyệt qua từng Học Kỳ trong Năm học đó --}}
                                    @foreach ($cacHocKy as $tenHK => $danhSachMon)
                                        <div class="card card-info card-outline mb-3">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title m-0 text-dark font-weight-bold" style="font-size: 1rem;">
                                                    <span class="badge badge-primary px-3 py-2 mr-2">{{ $tenHK }}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <table class="table table-bordered table-hover m-0">
                                                    <thead>
                                                        <tr class="bg-gray-light text-center" style="font-size: 0.9rem;">
                                                            <th style="width: 5%;">STT</th>
                                                            <th style="width: 35%; text-align: left;">Tên học phần (Mã HP)</th>
                                                            <th style="width: 20%;">Lớp học</th>
                                                            <th style="width: 25%; text-align: left;">Giảng viên giảng dạy</th>
                                                            <th style="width: 15%;">Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $stt = 1; @endphp
                                                        @foreach ($danhSachMon as $gd)
                                                            <tr>
                                                                <td class="text-center font-weight-bold" style="vertical-align: middle;">{{ $stt++ }}</td>
                                                                <td style="vertical-align: middle;">
                                                                    <span class="text-dark font-weight-bold">{{ $gd->tenHocPhan }}</span> 
                                                                    <small class="text-muted d-block">({{ $gd->maHocPhan }})</small>
                                                                </td>
                                                                <td class="text-center font-weight-bold text-secondary" style="vertical-align: middle;">
                                                                    {{ $gd->maLop }}
                                                                </td>
                                                                <td style="vertical-align: middle;">
                                                                    <i class="far fa-user text-muted mr-1"></i> {{ $gd->maGV }} - {{ $gd->hoGV }} {{ $gd->tenGV }}
                                                                </td>
                                                                <td class="text-center" style="vertical-align: middle;">
                                                                    <div class="btn-group-vertical btn-block">
                                                                        <a class="btn btn-xs btn-success mb-1" href="{{ route('covan.clo', ['maHocPhan' => $gd->maHocPhan, 'maGV' => $gd->maGV, 'maLop' => $maLop, 'maHKNH' => $gd->maHKNH]) }}">
                                                                            <i class="fas fa-chart-bar mr-1"></i> CĐR Học Phần
                                                                        </a>
                                                                        <a class="btn btn-xs btn-info" href="{{ route('covan.plo', ['maHocPhan' => $gd->maHocPhan, 'maGV' => $gd->maGV, 'maLop' => $maLop, 'maHKNH' => $gd->maHKNH]) }}">
                                                                            <i class="fas fa-chart-line mr-1"></i> CĐR CTĐT
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        @else
                            <div class="text-center text-danger p-5 border border-dashed rounded">
                                <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                                <p class="lead font-weight-bold">Không tìm thấy lịch sử học phần nào của lớp hành chính này!</p>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Bắt đầu phần thống kê PLO mới --}}
                <div class="card card-success card-outline mt-4">
                    <div class="card-header">
                        <h3 class="card-title" style="font-size: 1.2rem;">
                            <i class="fas fa-chart-pie mr-2 text-success"></i> 
                            Thống kê tỷ lệ đạt Chuẩn đầu ra Chương trình đào tạo (PLO) của lớp: <b class="text-danger">{{ $maLop }}</b>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(!empty($plo_stats))
                            <div class="alert alert-info shadow-sm border-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                <b>Tiêu chí đạt PLO:</b> Sinh viên có mức độ hoàn thành PLO <b>>= 70%</b> được tính là <b>Đạt</b> PLO đó.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-light text-center" style="font-size: 0.9rem;">
                                            <th style="width: 10%;">Chuẩn đầu ra</th>
                                            <th style="width: 45%; text-align: left;">Mô tả nội dung</th>
                                            <th style="width: 15%;">Đạt</th>
                                            <th style="width: 15%;">Chưa đạt</th>
                                            <th style="width: 15%;">Tỷ lệ Đạt / Chưa đạt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($plo_stats as $plo_id => $stat)
                                            <tr>
                                                <td class="text-center font-weight-bold" style="vertical-align: middle;">
                                                    <span class="badge badge-success px-2 py-1">{{ $stat['maCDR_CTDT_VB'] }}</span>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <span class="text-dark font-weight-bold" style="font-size: 0.95rem;">{{ $stat['tenCDR_CTDT'] }}</span>
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <b class="text-success">{{ $stat['dat'] }} SV</b>
                                                    <span class="text-muted d-block" style="font-size: 0.85rem;">({{ $stat['ty_le_dat'] }}%)</span>
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <b class="text-danger">{{ $stat['chua_dat'] }} SV</b>
                                                    <span class="text-muted d-block" style="font-size: 0.85rem;">({{ $stat['ty_le_chua_dat'] }}%)</span>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <div class="progress shadow-sm" style="height: 20px; border-radius: 10px; overflow: hidden;">
                                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" 
                                                             style="width: {{ $stat['ty_le_dat'] }}%; font-weight: bold;" 
                                                             aria-valuenow="{{ $stat['ty_le_dat'] }}" aria-valuemin="0" aria-valuemax="100">
                                                            @if($stat['ty_le_dat'] > 15)
                                                                {{ $stat['ty_le_dat'] }}%
                                                            @endif
                                                        </div>
                                                        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" 
                                                             style="width: {{ $stat['ty_le_chua_dat'] }}%; font-weight: bold;" 
                                                             aria-valuenow="{{ $stat['ty_le_chua_dat'] }}" aria-valuemin="0" aria-valuemax="100">
                                                            @if($stat['ty_le_chua_dat'] > 15 && $stat['ty_le_dat'] <= 85)
                                                                {{ $stat['ty_le_chua_dat'] }}%
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-warning p-4">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                                <p class="lead font-weight-bold">Chưa có thông tin chuẩn đầu ra (PLO) cho chương trình đào tạo của lớp này!</p>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- Kết thúc phần thống kê PLO mới --}}

            </div>
        </section>
    </div>
@endsection
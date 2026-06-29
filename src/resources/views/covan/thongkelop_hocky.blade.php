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
            </div>
        </section>
    </div>
@endsection
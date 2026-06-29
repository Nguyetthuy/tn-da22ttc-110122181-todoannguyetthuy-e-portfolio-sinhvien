@extends('covan.master')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    .content-wrapper { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); background: #fff; overflow: hidden; margin-bottom: 1.5rem; }
    
    .table-modern { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-modern th { background: #f8fafc; color: #475569; font-weight: 600; padding: 14px 16px; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0; text-align: center; vertical-align: middle; }
    .table-modern td { padding: 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; text-align: center; font-size: 0.95rem; }
    .table-modern tr:hover td { background-color: #f8fafc; }
    .table-modern tr:last-child td { border-bottom: none; }
    
    .page-title { font-weight: 700; color: #0f172a; letter-spacing: -0.02em; font-size: 1.5rem; line-height: 1.3; }
    .btn-back { display: inline-block; background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-weight: 600; transition: all 0.2s; text-decoration: none !important; }
    .btn-back:hover { background: #e2e8f0; color: #0f172a; }
</style>

<div class="content-wrapper" style="min-height: calc(100vh - 60px);">
    <div class="content-header pt-4 pb-3">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                <h1 class="page-title m-0 mb-3 mb-md-0">Thống kê Chuẩn Đầu Ra học phần: 
                    <span style="color: #2563EB;">{{ $hocPhan->tenHocPhan }}</span>
                    <span style="color: #64748b; font-size: 1.25rem;">({{ $hocPhan->maHocPhan }})</span>
                </h1>
                <a href="{{ url()->previous() }}" class="btn-back">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
    
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-modern">
                        <div class="card-body p-0">
                            <div class="p-4 border-bottom d-flex align-items-center justify-content-between" style="background: #f8fafc;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt mr-3" style="color: #2563EB; font-size: 1.25rem;"></i>
                                    <h5 class="m-0" style="font-weight: 700; color: #0f172a;">Bảng Thống kê CĐR Học phần (CLO)</h5>
                                </div>
                                <span class="badge" style="background: #e2e8f0; color: #334155; padding: 8px 12px; font-size: 0.9rem; border-radius: 8px;">
                                    Học kỳ: <strong style="color: #0f172a;">{{ $maHKNH ?? 'Tất cả học kỳ' }}</strong>
                                </span>
                            </div>
                            
                            <div class="table-responsive p-3">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="width: 5%;">STT</th>
                                            <th rowspan="2" style="width: 15%;">Mã CĐR HP</th>
                                            <th rowspan="2" style="width: 35%; text-align: left;">Tên CĐR HP</th>
                                            <th colspan="4" style="border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">Tỉ lệ mức đạt (%)</th>
                                            <th rowspan="2" style="width: 15%; background: #f0fdf4; color: #166534;">Tổng tỉ lệ đạt (%)</th>
                                        </tr>
                                        <tr>
                                            <th style="padding-top: 8px; font-weight: 700; color: #166534;">A</th>
                                            <th style="padding-top: 8px; font-weight: 700; color: #1e40af;">B</th>
                                            <th style="padding-top: 8px; font-weight: 700; color: #854d0e;">C</th>
                                            <th style="padding-top: 8px; font-weight: 700; color: #c2410c;">D</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @if(count($bieuDo) > 0)
                                            @foreach ($bieuDo as $bd)
                                                @php
                                                    $sum = 0;
                                                    for ($t = 2; $t <= 5; $t++) {
                                                        $sum += floatval($bd[$t] ?? 0);
                                                    }
                                                @endphp
                                                <tr>
                                                    <td style="font-weight: 500;">{{ $i++ }}</td>
                                                    <td><span style="font-weight: 600; color: #334155; font-size: 0.95rem;">{{ $bd[0] }}</span></td>
                                                    <td style="text-align: left; font-weight: 500; color: #0f172a; line-height: 1.4;">{{ $bd[1] }}</td>
                                                    <td style="color: #166534; font-weight: 600;">{{ number_format($bd[2], 2) }}</td>
                                                    <td style="color: #1e40af; font-weight: 500;">{{ number_format($bd[3], 2) }}</td>
                                                    <td style="color: #854d0e; font-weight: 500;">{{ number_format($bd[4], 2) }}</td>
                                                    <td style="color: #c2410c; font-weight: 500;">{{ number_format($bd[5], 2) }}</td>
                                                    <td style="background: #f0fdf4;">
                                                        <span style="font-weight: 700; font-size: 1.1rem; color: {{ $sum >= 50 ? '#166534' : '#b45309' }};">
                                                            {{ number_format($sum, 2) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center p-5 text-muted">
                                                    <i class="fas fa-box-open fa-3x mb-3" style="color: #94a3b8;"></i>
                                                    <h5 style="font-weight: 600;">Chưa có dữ liệu</h5>
                                                    <p class="mb-0">Môn học này chưa có dữ liệu chấm điểm thống kê chuẩn đầu ra học phần!</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
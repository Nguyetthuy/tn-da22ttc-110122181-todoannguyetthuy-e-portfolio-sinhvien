@extends('covan.master')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    .content-wrapper { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); background: #fff; overflow: hidden; margin-bottom: 1.5rem; }
    
    .table-modern { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-modern th { background: #f8fafc; color: #475569; font-weight: 600; padding: 14px 16px; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0; vertical-align: middle; }
    .table-modern td { padding: 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; font-size: 0.95rem; transition: background 0.15s; }
    .table-modern tr:hover td { background-color: #f8fafc; }
    .table-modern tr:last-child td { border-bottom: none; }
    
    .page-title { font-weight: 700; color: #0f172a; letter-spacing: -0.02em; font-size: 1.5rem; line-height: 1.3; }
    .btn-back { display: inline-block; background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-weight: 600; transition: all 0.2s; text-decoration: none !important; }
    .btn-back:hover { background: #e2e8f0; color: #0f172a; }
    
    .btn-action { display: inline-flex; align-items: center; border-radius: 8px; padding: 6px 12px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s; text-decoration: none !important; border: none; cursor: pointer; white-space: nowrap; margin-bottom: 4px; }
    .btn-action-primary { background: #eff6ff; color: #2563EB; }
    .btn-action-primary:hover { background: #dbeafe; color: #1d4ed8; }
    .btn-action-success { background: #f0fdf4; color: #16a34a; }
    .btn-action-success:hover { background: #dcfce7; color: #15803d; }
    .btn-action-info { background: #fefce8; color: #ca8a04; }
    .btn-action-info:hover { background: #fef9c3; color: #a16207; }
</style>

<div class="content-wrapper" style="min-height: calc(100vh - 60px);">
    <div class="content-header pt-4 pb-3">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                <div>
                    <h1 class="page-title m-0 mb-1">Học phần của Sinh viên: 
                        @if(isset($sinhVien))
                            <span style="color: #2563EB;">{{ $sinhVien->HoSV ?? '' }} {{ $sinhVien->TenSV ?? ($sinhVien->hoTen ?? '') }}</span>
                            <span style="color: #64748b; font-size: 1.25rem;">({{ $sinhVien->maSSV }})</span>
                        @endif
                    </h1>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('covan.sinhvien', $sinhVien->maLop ?? '') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="container-fluid">
            <div class="alert alert-success alert-dismissible" style="border-radius: 12px; border: none; background: #dcfce7; color: #166534;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 class="mb-0"><i class="icon fas fa-check mr-2"></i> {{ session('success') }}</h5>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="container-fluid">
            <div class="alert alert-warning alert-dismissible" style="border-radius: 12px; border: none; background: #fef9c3; color: #854d0e;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 class="mb-0"><i class="icon fas fa-exclamation-triangle mr-2"></i> {{ session('warning') }}</h5>
            </div>
        </div>
    @endif

    <section class="content pb-5">
        <div class="container-fluid">
            <div class="card card-modern">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom d-flex align-items-center justify-content-between" style="background: #f8fafc;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book-open mr-3" style="color: #2563EB; font-size: 1.25rem;"></i>
                            <h5 class="m-0" style="font-weight: 700; color: #0f172a;">Danh sách học phần đã đăng ký / học</h5>
                        </div>
                        <span class="badge" style="background: #ecf1f7ff; color: #334155; padding: 8px 16px; font-size: 0.95rem; border-radius: 8px;">
                            <strong style="color: #2563EB; font-size: 1.1rem;">{{ count($giangday) }}</strong> học phần
                        </span>
                    </div>
                    
                    <div class="table-responsive p-3">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th style="width: 5%; text-align: center;">STT</th>
                                    <th style="width: 30%;">Học phần</th>
                                    <th style="width: 15%; text-align: center;">Học kì - Năm học</th>
                                    <th style="width: 20%;">Giảng viên</th>
                                    <th style="width: 30%; text-align: center;">Tùy chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @forelse($giangday as $data)
                                    <tr>
                                        <td style="text-align: center; font-weight: 500;">{{ $i++ }}</td>
                                        <td>
                                            <div style="font-weight: 600; color: #0f172a; font-size: 1.05rem; margin-bottom: 4px;">{{ $data->tenHocPhan }}</div>
                                            <span class="badge" style="background: #d7dee6ff; color: #063b85ff; padding: 4px 8px; font-size: 0.85rem; border-radius: 6px;">
                                                Mã HP: {{ $data->maHocPhan }}
                                            </span>
                                        </td>
                                        <td style="text-align: center; font-weight: 500; color: #09387aff;">{{ $data->maHKNH }}</td>
                                        <td>
                                            @if(isset($data->GV) && is_array($data->GV) && count($data->GV) > 0)
                                                <div class="d-flex flex-wrap" style="gap: 4px;">
                                                @foreach ($data->GV as $gv)
                                                    <span class="badge" style="background: #eff6ff; color: #1d4ed8; padding: 6px 10px; font-size: 0.85rem; border-radius: 6px; font-weight: 500;">
                                                        <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $gv->hoGV ?? '' }} {{ $gv->tenGV ?? '' }}
                                                    </span>
                                                @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted" style="font-style: italic;">Chưa phân công</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 8px;">
                                                <a href="{{ route('covan.xem-diem', ['maSSV' => $sinhVien->maSSV, 'maHocPhan' => $data->maHocPhan, 'maLop' => $data->maLop]) }}" 
                                                class="btn-action btn-action-primary">
                                                    <i class="fas fa-list-ol mr-2"></i> Xem điểm
                                                </a>
                                                            
                                                <a href="{{ route('covan.xem-cdr-hp', ['maSSV' => $sinhVien->maSSV, 'maHocPhan' => $data->maHocPhan, 'maLop' => $data->maLop]) }}" 
                                                class="btn-action btn-action-success" title="Xem Chuẩn đầu ra Học phần">
                                                    <i class="fas fa-chart-line mr-2"></i> CĐR_HP
                                                </a> 

                                                <a href="{{ route('covan.xem-cdr-ctdt', ['maSSV' => $sinhVien->maSSV, 'maHocPhan' => $data->maHocPhan, 'maLop' => $data->maLop]) }}" 
                                                class="btn-action btn-action-info" title="Xem Chuẩn đầu ra CTĐT">
                                                    <i class="fas fa-graduation-cap mr-2"></i> CĐR_CTDT
                                                </a> 
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-5 text-muted">
                                            <i class="fas fa-book fa-3x mb-3" style="color: #eaeef4ff;"></i>
                                            <h5 style="font-weight: 600;">Chưa có dữ liệu</h5>
                                            <p class="mb-0">Sinh viên chưa có dữ liệu học phần nào.</p>
                                        </td>
                                    </tr>
                                @endempty
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
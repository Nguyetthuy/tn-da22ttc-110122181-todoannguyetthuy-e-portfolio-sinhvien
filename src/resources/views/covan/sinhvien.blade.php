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
    
    .btn-action { display: inline-flex; align-items: center; border-radius: 8px; padding: 6px 12px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s; text-decoration: none !important; border: none; cursor: pointer; }
    .btn-action-primary { background: #eff6ff; color: #2563EB; }
    .btn-action-primary:hover { background: #dbeafe; color: #1d4ed8; }
    .btn-action-success { background: #f0fdf4; color: #16a34a; }
    .btn-action-success:hover { background: #dcfce7; color: #15803d; }
    
    .btn-modern { border-radius: 8px; font-weight: 500; letter-spacing: 0.02em; padding: 0.5rem 1.25rem; transition: all 0.2s; border: none; cursor: pointer; display: inline-flex; align-items: center; }
    
    .avatar-circle { width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #475569; font-size: 1rem; flex-shrink: 0; }
</style>

<div class="content-wrapper" style="min-height: calc(100vh - 60px);">
    <div class="content-header pt-4 pb-3">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                <div>
                    <h1 class="page-title m-0 mb-1">Danh sách Sinh viên Lớp: 
                        <span style="color: #2563EB;">{{ $lop->tenLop }}</span>
                    </h1>
                    <div style="color: #64748b; font-size: 0.95rem; font-weight: 500;">
                        <i class="fas fa-layer-group mr-1"></i> Chương trình đào tạo: {{ $lop->tenCT ?? 'Chưa cập nhật' }}
                    </div>
                </div>
                <div class="mt-3 mt-md-0 d-flex align-items-center" style="gap: 12px;">
                    <form action="{{ route('covan.lop.dongbocalop', $lop->maLop) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-modern shadow-sm" style="background-color: #2563EB; border-color: #2563EB;" onclick="return confirm('Hệ thống sẽ tổng hợp lại kết quả học tập để tính toán Chuẩn đầu ra cho tất cả sinh viên trong lớp học này. Việc này có thể mất ít phút. Tiếp tục?');">
                            <i class="fas fa-sync-alt mr-2"></i> Đồng bộ CĐR cả lớp
                        </button>
                    </form>
                    <a href="{{ url()->previous() }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="card card-modern">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom d-flex align-items-center justify-content-between" style="background: #f8fafc;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users mr-3" style="color: #2563EB; font-size: 1.25rem;"></i>
                            <h5 class="m-0" style="font-weight: 700; color: #0f172a;">Tổng số sinh viên</h5>
                        </div>
                        <span class="badge" style="background: #e2e8f0; color: #334155; padding: 8px 16px; font-size: 0.95rem; border-radius: 8px;">
                            <strong style="color: #2563EB; font-size: 1.1rem;">{{ count($dsSinhVien) }}</strong> sinh viên
                        </span>
                    </div>
                    
                    <div class="table-responsive p-3">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th style="width: 5%; text-align: center;">STT</th>
                                    <th style="width: 15%;">Mã Sinh Viên</th>
                                    <th style="width: 35%;">Thông tin Sinh viên</th>
                                    <th style="width: 45%; text-align: center;">Thao tác quản lý</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($dsSinhVien) > 0)
                                    @foreach($dsSinhVien as $i => $sv)
                                    <tr>
                                        <td style="text-align: center; font-weight: 500;">{{ $i + 1 }}</td>
                                        <td>
                                            <span class="badge" style="background: #f1f5f9; color: #475569; padding: 6px 12px; font-size: 0.9rem; border-radius: 6px; font-weight: 600;">
                                                {{ $sv->maSSV }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                
                                                <div>
                                                    <div style="font-weight: 600; color: #0f172a; font-size: 1.05rem;">{{ $sv->HoSV }} {{ $sv->TenSV }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 8px;">
                                                <a href="{{ route('covan.hockysv', $sv->maSSV) }}" class="btn-action btn-action-primary">
                                                    <i class="fas fa-chart-line mr-2"></i> KQ Học tập
                                                </a>
                                                <a href="{{ route('covan.danh-sach-hoc-phan', $sv->maSSV) }}" class="btn-action btn-action-primary">
                                                    <i class="fas fa-book mr-2"></i> Môn học
                                                </a>
                                                <a href="{{ route('covan.chuandaurasv', $sv->maSSV) }}" class="btn-action btn-action-success">
                                                    <i class="fas fa-award mr-2"></i> Chuẩn đầu ra
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center p-5 text-muted">
                                            <i class="fas fa-user-slash fa-3x mb-3" style="color: #94a3b8;"></i>
                                            <h5 style="font-weight: 600;">Danh sách trống</h5>
                                            <p class="mb-0">Lớp học này hiện tại chưa có sinh viên nào.</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
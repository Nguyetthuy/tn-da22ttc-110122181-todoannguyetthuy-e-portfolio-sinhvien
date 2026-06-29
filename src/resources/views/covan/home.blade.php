@extends('covan.master')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    .covan-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
        padding: 2rem 1.5rem;
        min-height: 100vh;
    }

    /* Greet Banner */
    .greet-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border-radius: 20px;
        padding: 2.5rem 2.25rem;
        color: #ffffff;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(30, 64, 175, 0.2);
    }
    .greet-banner::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }
    .greet-banner::after {
        content: "";
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }
    .greet-banner .greet-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: #ffffff;
        padding: 0.35rem 0.85rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .greet-banner h1 {
        font-size: 2.25rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }
    .greet-banner p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
        font-weight: 400;
    }

    /* Stats Cards */
    .stat-box {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }
    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        border-color: #dbeafe;
    }
    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1.25rem;
        flex-shrink: 0;
    }
    .stat-icon-wrapper.blue {
        background: #eff6ff;
        color: #2563eb;
    }
    .stat-icon-wrapper.indigo {
        background: #e0e7ff;
        color: #4f46e5;
    }
    .stat-icon-wrapper.emerald {
        background: #ecfdf5;
        color: #059669;
    }
    .stat-info .stat-num {
        font-size: 1.85rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    .stat-info .stat-lbl {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Section Header */
    .section-header-custom {
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .section-header-custom h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #0f172a;
        position: relative;
        padding-left: 0.75rem;
        margin-bottom: 0;
    }
    .section-header-custom h3::before {
        content: "";
        position: absolute;
        left: 0;
        top: 15%;
        height: 70%;
        width: 4px;
        background: #2563eb;
        border-radius: 2px;
    }

    /* Search Filter */
    .search-wrapper {
        position: relative;
        max-width: 320px;
        width: 100%;
    }
    .search-input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.25rem;
        border-radius: 999px;
        border: 1px solid #cbd5e1;
        font-size: 0.875rem;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .search-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .search-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
        pointer-events: none;
    }

    /* Class Card styling */
    .edu-class-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .edu-class-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #bfdbfe;
    }
    .edu-card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
    }
    .edu-card-header::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
    }
    .edu-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #dbeafe;
        color: #1e40af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .edu-card-title-container {
        flex-grow: 1;
    }
    .edu-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }
    .edu-card-badge {
        display: inline-block;
        padding: 0.2rem 0.60rem;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .edu-card-badge.active {
        background: #ecfdf5;
        color: #047857;
    }
    .edu-card-badge.ended {
        background: #fef2f2;
        color: #b91c1c;
    }

    .edu-card-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .edu-info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.925rem;
        color: #475569;
    }
    .edu-info-icon {
        font-size: 0.95rem;
        color: #3b82f6;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }
    .edu-info-label {
        color: #64748b;
        font-weight: 500;
    }
    .edu-info-value {
        font-weight: 600;
        color: #1e293b;
        margin-left: auto;
        text-align: right;
    }

    .edu-card-footer {
        padding: 1.25rem 1.5rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 0.75rem;
        background: #ffffff;
    }
    .btn-edu-primary {
        flex-grow: 1.3;
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.15);
        transition: all 0.2s ease;
    }
    .btn-edu-primary:hover {
        background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px -1px rgba(37, 99, 235, 0.25);
    }
    .btn-edu-secondary {
        flex-grow: 1;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
    }
    .btn-edu-secondary:hover {
        background: #eff6ff;
        color: #1e40af;
        border-color: #bfdbfe;
        transform: translateY(-1px);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #ffffff;
        border-radius: 20px;
        border: 1px dashed #cbd5e1;
        color: #64748b;
    }
    .empty-state-icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
</style>

@php
    $totalClasses = count($dsLop);
    $totalStudents = $dsLop->sum('soSinhVien');
    $activeCount = 0;
    foreach($dsLop as $lop) {
        if(!$lop->ngayKetThuc || \Carbon\Carbon::parse($lop->ngayKetThuc)->isFuture() || \Carbon\Carbon::parse($lop->ngayKetThuc)->isToday()) {
            $activeCount++;
        }
    }
@endphp

<div class="content-wrapper covan-dashboard">
    <div class="container-fluid">
        <!-- Greet Banner -->
        <div class="greet-banner">
            <div class="greet-badge">
                <i class="fas fa-graduation-cap"></i> Môi trường Giáo dục chuyên nghiệp
            </div>
            <h1>Xin chào, {{ Session::get('hoGV') }} {{ Session::get('tenGV') }}!</h1>
            <p>Chào mừng thầy/cô quay trở lại với Hệ thống Cố vấn học tập. Quản lý lớp học hành chính và theo dõi tiến độ sinh viên của bạn tại đây.</p>
        </div>

        <!-- Quick Stats Grid -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="stat-box">
                    <div class="stat-icon-wrapper blue">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-num">{{ $totalClasses }}</div>
                        <div class="stat-lbl">Tổng số lớp</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-box">
                    <div class="stat-icon-wrapper indigo">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-num">{{ $totalStudents }}</div>
                        <div class="stat-lbl">Tổng số sinh viên</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-box">
                    <div class="stat-icon-wrapper emerald">
                        <i class="fas fa-toggle-on"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-num">{{ $activeCount }}</div>
                        <div class="stat-lbl">Lớp đang phụ trách</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Header -->
        <div class="section-header-custom">
            <h3>Danh sách lớp đang quản lý</h3>
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="class-search" class="search-input" placeholder="Tìm kiếm lớp học...">
            </div>
        </div>

        <!-- Class List Grid -->
        <div class="row" id="class-list-container">
            @forelse($dsLop as $lop)
                @php
                    $isEnded = $lop->ngayKetThuc && \Carbon\Carbon::parse($lop->ngayKetThuc)->isPast() && !\Carbon\Carbon::parse($lop->ngayKetThuc)->isToday();
                @endphp
                <div class="col-lg-4 col-md-6 mb-4 class-card-col">
                    <div class="edu-class-card">
                        <div class="edu-card-header">
                            <div class="edu-card-icon">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div class="edu-card-title-container">
                                <div class="edu-card-title">{{ $lop->tenLop }}</div>
                                @if($isEnded)
                                    <span class="edu-card-badge ended">Đã kết thúc</span>
                                @else
                                    <span class="edu-card-badge active">Đang phụ trách</span>
                                @endif
                            </div>
                        </div>
                        <div class="edu-card-body">
                            <div class="edu-info-item">
                                <i class="fas fa-graduation-cap edu-info-icon"></i>
                                <span class="edu-info-label">Chương trình đào tạo:</span>
                                <span class="edu-info-value edu-info-value-ctdt">{{ $lop->tenCT ?? 'Chưa rõ' }}</span>
                            </div>
                            <div class="edu-info-item">
                                <i class="fas fa-users edu-info-icon"></i>
                                <span class="edu-info-label">Sĩ số lớp:</span>
                                <span class="edu-info-value">{{ $lop->soSinhVien }} sinh viên</span>
                            </div>
                            <div class="edu-info-item">
                                <i class="fas fa-calendar-alt edu-info-icon"></i>
                                <span class="edu-info-label">Ngày bắt đầu:</span>
                                <span class="edu-info-value">{{ $lop->ngayBatDau ? \Carbon\Carbon::parse($lop->ngayBatDau)->format('d/m/Y') : 'Chưa rõ' }}</span>
                            </div>
                            @if($lop->ngayKetThuc)
                            <div class="edu-info-item">
                                <i class="fas fa-calendar-times edu-info-icon"></i>
                                <span class="edu-info-label">Ngày kết thúc:</span>
                                <span class="edu-info-value">{{ \Carbon\Carbon::parse($lop->ngayKetThuc)->format('d/m/Y') }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="edu-class-card-actions edu-card-footer text-muted d-flex justify-content-center align-items-center" style="font-size: 0.85rem; background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 1rem;">
                            <span><i class="fas fa-user-check mr-1 text-success"></i> Thầy/cô đang phụ trách lớp này</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h4>Chưa có lớp phụ trách</h4>
                        <p>Thầy/cô chưa được phân công quản lý lớp hành chính nào hoặc các lớp đã được xóa.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('class-search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
                const cards = document.querySelectorAll('.class-card-col');
                
                cards.forEach(card => {
                    const titleText = card.querySelector('.edu-card-title').textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    const ctdtText = card.querySelector('.edu-info-value-ctdt') ? card.querySelector('.edu-info-value-ctdt').textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "") : '';
                    
                    if (titleText.includes(query) || ctdtText.includes(query)) {
                        card.style.setProperty('display', 'block', 'important');
                    } else {
                        card.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        }
    });
</script>
@endsection
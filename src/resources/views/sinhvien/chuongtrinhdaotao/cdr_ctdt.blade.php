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

    <section class="content">
        <div class="container-fluid">

            {{-- Thông tin chương trình --}}
            @if(count($chuongTrinh) > 0)
            @php $ct = $chuongTrinh[0]; @endphp
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-graduation-cap"></i> {{ $ct->tenCT }}
                    </h3>
                </div>
                <div class="card-body">
                    <p><b>Bậc đào tạo:</b> {{ $ct->tenBac }}</p>
                    <p><b>Hệ:</b> {{ $ct->tenHe }}</p>
                </div>
            </div>

            {{-- Chuẩn đầu ra --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chuẩn đầu ra chương trình đào tạo</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã CĐR</th>
                                <th>Tên chuẩn đầu ra</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @forelse($cdrCTDT as $cdr)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $cdr->maCDR_CTDT_VB }}</td>
                                <td>{{ $cdr->tenCDR_CTDT }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Chưa có dữ liệu CĐR</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @else
            <div class="alert alert-warning">
                Không tìm thấy chương trình đào tạo cho lớp của bạn.
            </div>
            @endif

        </div>
    </section>
</div>
@endsection

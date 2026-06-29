@extends('covan.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-dark">Điểm học phần</h1>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <b>{{ $hocPhan->maHocPhan_VB ?? '' }} - {{ $hocPhan->tenHocPhan ?? '' }}</b>
                        @if($maHKNH)
                            <span class="badge badge-info ml-2">{{ $maHKNH }}</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ asset('sinh-vien/hoc-phan') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($sv)
                    <p><b>MSSV:</b> {{ $sv->maSSV }} | <b>Họ tên:</b> {{ $sv->HoSV }} {{ $sv->TenSV }}</p>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                @foreach($hinhthucdg as $ht)
                                <th>{{ $ht->tenLoaiDG }}<br><small>Trọng số {{ $ht->trongSo }}%</small></th>
                                @endforeach
                                <th>Điểm TB Học Phần</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                    $tongDiem = 0;
                                    $tongTS   = 0;
                                @endphp
                                @foreach($hinhthucdg as $ht)
                                    @php
                                        $diem = 'VT'; $tong = 0; $dem = 0;
                                        foreach($diem_pc as $d) {
                                            if($d->maLoaiDG == $ht->maLoaiDG) {
                                                $tong += $d->diemSo;
                                                $dem++;
                                            }
                                        }
                                        if($dem > 0) {
                                            $diem = round($tong / $dem, 1);
                                            $tongDiem += $diem * $ht->trongSo;
                                        }
                                        $tongTS += $ht->trongSo;
                                    @endphp
                                    <td>{{ $diem }}</td>
                                @endforeach
                                <td><b>{{ $tongTS > 0 ? round($tongDiem / $tongTS, 1) : 0 }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

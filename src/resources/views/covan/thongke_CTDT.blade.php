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
                <h1 class="page-title m-0 mb-3 mb-md-0">
                    Thống kê CĐR CTĐT học phần: 
                    <span style="color: #2563EB;">
                        @if (Session::get('language') && Session::get('language')=='en')
                            {{ $hocPhan->tenHocPhanEN }}
                        @else
                            {{ $hocPhan->tenHocPhan }}
                        @endif 
                    </span>
                    <span style="color: #64748b; font-size: 1.25rem;">({{ $hocPhan->maHocPhan_VB ?? $hocPhan->maHocPhan }})</span>
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
                                    <i class="fas fa-graduation-cap mr-3" style="color: #2563EB; font-size: 1.25rem;"></i>
                                    <h5 class="m-0" style="font-weight: 700; color: #0f172a;">Bảng Thống kê CĐR Chương trình đào tạo (PLO)</h5>
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
                                            <th rowspan="2" style="width: 15%;">Mã CĐR CTĐT</th>
                                            <th rowspan="2" style="width: 30%; text-align: left;">Tên CĐR CTĐT</th>
                                            <th colspan="4" style="border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">Tỉ lệ mức đạt (%)</th>
                                            <th rowspan="2" style="width: 12%; background: #f0fdf4; color: #166534;">Tổng đạt (%)</th>
                                            <th rowspan="2" style="width: 10%;">Chưa đạt (%)</th>
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
                                        @if(count($thong_ke_elo) > 0)
                                            @foreach ($thong_ke_elo as $bd)
                                                @php
                                                    $divisor = ($bd->Tong > 0) ? $bd->Tong : 1;
                                                    
                                                    $rateA = ($bd->Tong > 0) ? ($bd->A / $divisor) * 100 : 0;
                                                    $rateB = ($bd->Tong > 0) ? ($bd->B / $divisor) * 100 : 0;
                                                    $rateC = ($bd->Tong > 0) ? ($bd->C / $divisor) * 100 : 0;
                                                    $rateD = ($bd->Tong > 0) ? ($bd->D / $divisor) * 100 : 0;
                                                    
                                                    $totalAchieved = $rateA + $rateB + $rateC + $rateD;
                                                    $rateFail = ($bd->Tong > 0) ? ($bd->Chua_dat / $divisor) * 100 : 0;
                                                @endphp
                                                <tr>
                                                    <td style="font-weight: 500;">{{ $i++ }}</td>
                                                    <td><span style="font-weight: 600; color: #334155; font-size: 0.95rem;">{{ $bd->maCDR_CTDT_VB }}</span></td>
                                                    <td style="text-align: left; font-weight: 500; color: #0f172a; line-height: 1.4;">{{ $bd->tenCDR_CTDT }}</td>
                                                    <td style="color: #166534; font-weight: 600;">{{ number_format($rateA, 2) }}</td>
                                                    <td style="color: #1e40af; font-weight: 500;">{{ number_format($rateB, 2) }}</td>
                                                    <td style="color: #854d0e; font-weight: 500;">{{ number_format($rateC, 2) }}</td>
                                                    <td style="color: #c2410c; font-weight: 500;">{{ number_format($rateD, 2) }}</td>
                                                    <td style="background: #f0fdf4; color: #166534; font-weight: 700;">
                                                        {{ number_format($totalAchieved, 2) }}
                                                    </td>
                                                    <td style="color: #dc2626; font-weight: 600;">{{ number_format($rateFail, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center p-5 text-muted">
                                                    <i class="fas fa-box-open fa-3x mb-3" style="color: #94a3b8;"></i>
                                                    <h5 style="font-weight: 600;">Chưa có dữ liệu</h5>
                                                    <p class="mb-0">Học phần này chưa ghi nhận dữ liệu liên kết hoặc điểm số tích lũy cho CĐR ELO.</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Hiển thị biểu đồ cột Stacked Bar Chart --}}
                    <div class="card card-modern">
                        <div class="card-body p-4">
                            <h5 class="mb-4" style="font-weight: 700; color: #0f172a; display: flex; align-items: center;">
                                <i class="fas fa-chart-bar mr-3" style="color: #22C55E;"></i> Biểu đồ trực quan mức đạt PLO (%)
                            </h5>
                            <div class="chart">
                                <canvas id="barChart" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var label = [];
        var gioi = [], kha = [], tb = [], yeu = [], kem = [];
        
        @if(isset($thong_ke_elo) && count($thong_ke_elo) > 0)
            @foreach($thong_ke_elo as $bd)
                @php
                    $divisor = ($bd->Tong > 0) ? $bd->Tong : 1;
                    $rateA = ($bd->Tong > 0) ? ($bd->A / $divisor) * 100 : 0;
                    $rateB = ($bd->Tong > 0) ? ($bd->B / $divisor) * 100 : 0;
                    $rateC = ($bd->Tong > 0) ? ($bd->C / $divisor) * 100 : 0;
                    $rateD = ($bd->Tong > 0) ? ($bd->D / $divisor) * 100 : 0;
                    $rateFail = ($bd->Tong > 0) ? ($bd->Chua_dat / $divisor) * 100 : 0;
                @endphp
                label.push('{{ $bd->maCDR_CTDT_VB }}');
                gioi.push({{ round($rateA, 2) }});
                kha.push({{ round($rateB, 2) }});
                tb.push({{ round($rateC, 2) }});
                yeu.push({{ round($rateD, 2) }});
                kem.push({{ round($rateFail, 2) }});
            @endforeach

            var areaChartData = {
                labels: label,
                datasets: [
                    { label: 'Mức A', backgroundColor: '#22c55e', data: gioi },
                    { label: 'Mức B', backgroundColor: '#3b82f6', data: kha },
                    { label: 'Mức C', backgroundColor: '#eab308', data: tb },
                    { label: 'Mức D', backgroundColor: '#f97316', data: yeu },
                    { label: 'Chưa đạt', backgroundColor: '#ef4444', data: kem }
                ]
            };

            var barChartCanvas = document.getElementById('barChart').getContext('2d');
            new Chart(barChartCanvas, {
                type: 'bar',
                data: areaChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        labels: {
                            fontFamily: 'Inter, sans-serif',
                            fontSize: 13,
                            fontColor: '#475569'
                        }
                    },
                    animation: {
                        duration: 500,
                        onComplete: function () {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;
                            
                            ctx.font = Chart.helpers.fontString(11, 'bold', 'Inter, sans-serif');
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillStyle = '#475569';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    if (data > 0) {
                                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                    }
                                });
                            });
                        }
                    },
                    scales: {
                        xAxes: [{ 
                            stacked: false,
                            gridLines: { 
                                display: true, 
                                color: '#e2e8f0',
                                borderDash: [4, 4],
                                drawBorder: false,
                                offsetGridLines: true
                            },
                            ticks: { fontFamily: 'Inter, sans-serif', fontColor: '#64748b' }
                        }],
                        yAxes: [{ 
                            stacked: false,
                            ticks: { 
                                min: 0, 
                                max: 100,
                                fontFamily: 'Inter, sans-serif', 
                                fontColor: '#64748b',
                                callback: function(value) { return value }
                            },
                            gridLines: { borderDash: [4, 4], color: '#e2e8f0', drawBorder: false }
                        }]
                    }
                }
            });
        @endif
    });
</script>
@endsection
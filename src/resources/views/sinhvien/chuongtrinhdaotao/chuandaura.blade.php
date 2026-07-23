@extends('sinhvien.master')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    .content-wrapper { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); background: #fff; overflow: hidden; }
    
    .stat-card { border-radius: 16px; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; flex-shrink: 0; }
    .bg-blue-light { background: #eff6ff; color: #2563EB; }
    .bg-green-light { background: #f0fdf4; color: #22C55E; }
    .bg-yellow-light { background: #fefce8; color: #eab308; }
    
    .plo-header { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 12px; border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.2s; }
    .plo-header:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    
    .table-modern { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-modern th { background: #f8fafc; color: #64748b; font-weight: 600; padding: 12px 16px; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; }
    .table-modern td { padding: 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; transition: background 0.15s; }
    .table-modern tr:hover td { background-color: #f8fafc; }
    .table-modern tr:last-child td { border-bottom: none; }
    
    .badge-score-A { background: #dcfce7; color: #166534; } /* Điểm A - Xanh lá */
    .badge-score-B { background: #dbeafe; color: #1e40af; } /* Điểm B - Xanh dương */
    .badge-score-C { background: #ffedd5; color: #c2410c; } /* Điểm C - Cam */
    .badge-score-D { background: #fee2e2; color: #991b1b; } /* Điểm D - Đỏ */
    
    .progress-bar-custom { height: 8px; border-radius: 4px; background: #e2e8f0; overflow: hidden; display: inline-block; vertical-align: middle; }
    .progress-bar-fill { height: 100%; border-radius: 4px; transition: width 0.5s ease; }
    
    .year-header { 
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); 
        padding: 14px 22px; 
        font-weight: 700; 
        color: white; 
        cursor: pointer; 
        border-radius: 10px; 
        margin-bottom: 14px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        border: none; 
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06); 
        transition: all 0.2s ease; 
    }
    .year-header:hover { 
        transform: translateY(-1px); 
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2); 
        filter: brightness(1.1); 
    }
    
    .btn-modern { border-radius: 8px; font-weight: 500; letter-spacing: 0.02em; padding: 0.5rem 1.25rem; transition: all 0.2s; }
    
    /* Mobile responsive card view for table */
    @media (max-width: 768px) {
        .table-modern thead { display: none; }
        .table-modern tr { display: block; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 12px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .table-modern td { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #e2e8f0 !important; }
        .table-modern tr td:last-child { border-bottom: none !important; }
        .table-modern td::before { content: attr(data-label); font-weight: 600; color: #64748b; font-size: 0.85rem; margin-right: 15px; }
        .mobile-hide { display: none !important; }
        .plo-header .flex-md-row { flex-direction: column !important; align-items: flex-start !important; }
        .plo-header .justify-content-between { margin-top: 15px; width: 100%; }
    }
</style>

<div class="content-wrapper" style="min-height: 31px;">
  <div class="content-header pt-4 pb-3">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="m-0" style="font-weight: 700; color: #0f172a; letter-spacing: -0.02em;">Chuẩn đầu ra (PLO) Dashboard</h3>
        <form action="{{ asset('/sinh-vien/chuan-dau-ra-ctdt/cap-nhat-plo-clo') }}" method="POST" class="mt-3 mt-md-0">
            @csrf
            <button type="submit" class="btn btn-primary btn-modern shadow-sm" style="background-color: #2563EB; border-color: #2563EB;" onclick="return confirm('Hệ thống sẽ tổng hợp lại kết quả học tập để tính toán Chuẩn đầu ra. Việc này có thể mất ít phút. Tiếp tục?');">
                <i class="fas fa-sync-alt mr-2"></i> Đồng bộ dữ liệu
            </button>
        </form>
      </div>
    </div>
  </div>

  <section class="content pb-5">
    <div class="container-fluid">
      


      <!-- PLO Details (Accordion) -->
      <div class="row">
        <div class="col-12">
            <div class="card card-modern mb-4">
                <div class="card-body p-4 p-md-5">
                    <h5 class="mb-4" style="font-weight: 700; color: #0f172a; display: flex; align-items: center;"><i class="fas fa-layer-group mr-3" style="color: #2563EB;"></i> Chi tiết tích luỹ Chuẩn đầu ra (PLO)</h5>
                    
                    <div class="accordion" id="accordionPLO">
                        @forelse($thongkePLO as $plo)
                            @php
                                $tongDat = round($plo->ty_le_dat_tb, 2);
                                if ($tongDat > 100) $tongDat = 100;
                                
                                if ($tongDat >= 85)      { $badgeCls = 'badge-score-A'; $progressColor = '#22C55E'; $label = 'Tốt'; }
                                elseif ($tongDat >= 70)  { $badgeCls = 'badge-score-B'; $progressColor = '#2563EB'; $label = 'Khá'; }
                                elseif ($tongDat >= 50)  { $badgeCls = 'badge-score-C'; $progressColor = '#f59e0b'; $label = 'Trung bình'; }
                                else                     { $badgeCls = 'badge-score-D'; $progressColor = '#ef4444'; $label = 'Chưa đạt'; }
                            @endphp
                            
                            <div class="mb-4">
                                <div class="plo-header p-3 px-md-4" data-toggle="collapse" data-target="#collapse{{ $loop->index }}">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                        <div class="d-flex align-items-center mb-2 mb-md-0 flex-grow-1 pr-3">
                                            <span class="badge" style="background: #2563eb; color: white; padding: 8px 12px; font-size: 1rem; border-radius: 8px; margin-right: 15px;">{{ $plo->maCDR_CTDT_VB }}</span>
                                            <span style="font-weight: 600; font-size: 1.05rem; color: #334155; line-height: 1.4;">{{ $plo->tenCDR_CTDT }}</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between flex-shrink-0">
                                            <div class="mr-4 text-right mobile-hide">
                                                <div class="text-muted mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Mức độ đạt</div>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress-bar-custom mr-2" style="width: 100px;">
                                                        <div class="progress-bar-fill" style="width: {{ $tongDat }}%; background-color: {{ $progressColor }};"></div>
                                                    </div>
                                                    <span style="font-weight: 700; font-size: 1rem; color: {{ $progressColor }}; min-width: 45px; display: inline-block; text-align: left;">{{ $tongDat }}%</span>
                                                </div>
                                            </div>
                                            <!-- Hiển thị trên mobile -->
                                            <div class="d-md-none d-flex align-items-center mr-3">
                                                <span style="font-weight: 700; font-size: 1rem; color: {{ $progressColor }};">{{ $tongDat }}%</span>
                                            </div>
                                            
                                            <i class="fas fa-chevron-down text-slate-400 ml-2"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="collapse{{ $loop->index }}" class="collapse mt-3">
                                    <div class="px-2 px-md-3 pb-3">
                                        @if(isset($chiTietPLO[$plo->maCDR_CTDT_VB]) && count($chiTietPLO[$plo->maCDR_CTDT_VB]) > 0)
                                            @foreach($chiTietPLO[$plo->maCDR_CTDT_VB] as $namHoc => $dsHocKy)
                                                
                                                <!-- Cấp Accordion 2: Năm học -->
                                                <div class="year-header mt-3" data-toggle="collapse" data-target="#collapseYear_{{ $loop->parent->index }}_{{ $loop->index }}">
                                                    <span><i class="far fa-calendar-alt mr-2" style="color: #93c5fd;"></i> Năm học {{ $namHoc }}</span>
                                                    <i class="fas fa-angle-down"></i>
                                                </div>
                                                
                                                <div id="collapseYear_{{ $loop->parent->index }}_{{ $loop->index }}" class="collapse show px-2">
                                                    @foreach($dsHocKy as $hocKy => $dsHocPhan)
                                                        @php
                                                            $firstCourse = collect($dsHocPhan)->first();
                                                            $dgHocKy = round($firstCourse->ty_le_dg_hocky ?? 0, 2);
                                                        @endphp
                                                        <div class="mt-3 mb-4">
                                                            <div class="px-3 py-2 mb-3 d-flex justify-content-between align-items-center" style="font-weight: 700; color: #1e293b; font-size: 1.05rem; border-left: 4px solid #3b82f6; background-color: #eff6ff; border-radius: 4px 8px 8px 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                                                <span class="ml-1"><i class="fas fa-graduation-cap mr-2" style="color: #2563eb;"></i>{{ $hocKy }}</span>
                                                                <span class="badge" style="background: #2563eb; color: #ffffff; padding: 6px 14px; font-size: 0.85rem; border-radius: 20px; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);">
                                                                    Đóng góp HK: <strong style="font-size: 0.95rem;">{{ $dgHocKy }}%</strong>
                                                                </span>
                                                            </div>
                                                            <table class="table-modern">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 35%;">Tên Môn học</th>
                                                                        <th style="width: 10%; text-align: center;">Tín chỉ</th>
                                                                        <th style="width: 20%; text-align: center;">Điểm CĐR</th>
                                                                        <th style="width: 20%; text-align: center;">Mức đạt</th>
                                                                        <th style="width: 15%; text-align: right;">Đóng góp (%)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($dsHocPhan as $chitiet)
                                                                        @php
                                                                            $pct = $chitiet->ty_le_dat;
                                                                            $diemCDR = round($pct / 10, 1);
                                                                            
                                                                            if ($pct >= 90)     { $mucDat = 'A'; $scoreCls = 'badge-score-A'; }
                                                                            elseif ($pct >= 70) { $mucDat = 'B'; $scoreCls = 'badge-score-B'; }
                                                                            elseif ($pct >= 55) { $mucDat = 'C'; $scoreCls = 'badge-score-C'; }
                                                                            elseif ($pct >= 40) { $mucDat = 'D'; $scoreCls = 'badge-score-C'; }
                                                                            else                { $mucDat = 'Chưa đạt'; $scoreCls = 'badge-score-D'; }
                                                                            
                                                                            $tyLe = round($chitiet->ty_le_dong_gop ?? 0, 2);
                                                                            $tyLeColor = '#2563EB';
                                                                        @endphp
                                                                        <tr>
                                                                            <td data-label="Môn học">
                                                                                <div style="font-weight: 500;">{{ $chitiet->tenHocPhan }}</div>
                                                                            </td>
                                                                            <td data-label="Tín chỉ" class="text-md-center text-right">
                                                                                {{ $chitiet->tongSoTinChi }}
                                                                            </td>
                                                                            <td data-label="Điểm CĐR" class="text-md-center text-right">
                                                                                <span style="font-weight: 700; font-size: 1.1rem; color: #0f172a;">{{ $diemCDR }}</span>
                                                                            </td>
                                                                            <td data-label="Mức đạt" class="text-md-center text-right">
                                                                                <span class="badge {{ $scoreCls }} px-2 py-1 notranslate" translate="no" style="border-radius: 6px; font-size: 0.85rem;">{{ $mucDat }}</span>
                                                                            </td>
                                                                            <td data-label="Đóng góp">
                                                                                <div class="d-flex align-items-center justify-content-md-end justify-content-end">
                                                                                    <span style="font-weight: 600; font-size: 0.95rem; color: #334155;">{{ $tyLe }}</span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center p-4 text-muted" style="background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                                                <i class="fas fa-box-open fa-2x mb-2" style="color: #94a3b8;"></i>
                                                <p class="mb-0">Chưa có dữ liệu môn học.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted" style="background: #fff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                                <i class="fas fa-folder-open fa-3x mb-3" style="color: #94a3b8;"></i>
                                <h5 style="font-weight: 600;">Chưa có dữ liệu Chuẩn đầu ra</h5>
                                <p class="mb-0">Vui lòng bấm nút <strong>Đồng bộ dữ liệu</strong> ở phía trên.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
            <div class="card card-modern mb-4">
                <div class="card-body p-4 p-md-5">
                    <h5 class="mb-4" style="font-weight: 700; color: #0f172a; display: flex; align-items: center;"><i class="fas fa-chart-line mr-3" style="color: #2563EB;"></i> Tỷ lệ đóng góp CĐR theo Học kỳ (%)</h5>
                    <div id="heatmapChart" style="min-height: 400px; margin-left: -10px;"></div>
                </div>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
            <div class="card card-modern mb-4">
                <div class="card-body p-4 p-md-5">
                    <h5 class="mb-4" style="font-weight: 700; color: #0f172a; display: flex; align-items: center;"><i class="fas fa-chart-bar mr-3" style="color: #22C55E;"></i> Mức độ hoàn thành Chuẩn đầu ra (%)</h5>
                    <div id="ploBarChart" style="min-height: 380px;"></div>
                </div>
            </div>
        </div>
      </div>

    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // --- Biểu đồ cột: Mức độ hoàn thành CĐR ---
    const labelsPLO = {!! json_encode($chartLabelPLO ?? []) !!};
    let dataPLO   = {!! json_encode($chartDataPLO ?? []) !!};

    if (labelsPLO.length > 0) {
        // Giới hạn giá trị tối đa là 100
        dataPLO = dataPLO.map(v => v > 100 ? 100 : v);

        var barOptions = {
            series: [{ name: 'Mức độ hoàn thành (%)', data: dataPLO }],
            chart: { 
                type: 'bar', 
                height: 380, 
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: { 
                    borderRadius: 6, 
                    columnWidth: '45%',
                    dataLabels: {
                        position: 'top', // Đưa số liệu lên đỉnh cột
                    }
                }
            },
            colors: ['#22C55E'], // Chỉ sử dụng 1 màu (Xanh lá)
            dataLabels: {
                enabled: true,
                formatter: v => v > 0 ? v : '', // Bỏ dấu %
                offsetY: -20, // Đẩy số liệu lên trên viền của cột
                style: { fontSize: '12px', colors: ['#475569'], fontWeight: 600 }
            },
            xaxis: { 
                categories: labelsPLO,
                labels: { style: { colors: '#64748b', fontWeight: 500 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 5,
                labels: { formatter: v => v + '%', style: { colors: '#64748b', fontWeight: 500 } }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
            },
            legend: { show: false },
            tooltip: {
                theme: 'light',
                y: { formatter: v => v > 0 ? v + '%' : 'Chưa có dữ liệu' }
            }
        };

        new ApexCharts(document.getElementById('ploBarChart'), barOptions).render();
    } else {
        document.getElementById('ploBarChart').innerHTML =
            '<div class="text-center text-muted py-5"><i class="fas fa-chart-bar fa-3x mb-3 text-slate-300"></i><br>Chưa có dữ liệu biểu đồ.</div>';
    }

    // --- Biểu đồ Heatmap (ApexCharts) ---
    const heatmapSeriesData = {!! json_encode($heatmapSeries ?? []) !!};
    
    if (heatmapSeriesData.length > 0) {
        var options = {
            series: heatmapSeriesData,
            chart: {
                height: 500,
                type: 'heatmap',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                heatmap: {
                    shadeIntensity: 0.5,
                    radius: 0,
                    useFillColorAsStroke: false,
                    colorScale: {
                        ranges: [
                            { from: -1,     to: 0,       color: '#f8fafc' },
                            { from: 0.001,  to: 5,       color: '#dbeafe' },
                            { from: 5.001,  to: 10,      color: '#bfdbfe' },
                            { from: 10.001, to: 15,      color: '#93c5fd' },
                            { from: 15.001, to: 20,      color: '#60a5fa' },
                            { from: 20.001, to: 30,      color: '#3b82f6' },
                            { from: 30.001, to: 40,      color: '#2563eb' },
                            { from: 40.001, to: 60,      color: '#1d4ed8' },
                            { from: 60.001, to: 100,     color: '#1e40af' }
                        ]
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val !== null && val !== undefined ? val : 0;
                },
                style: { 
                    fontSize: '13px',
                    fontWeight: 700,
                    colors: [
                        function({ value }) {
                            return value > 15 ? '#ffffff' : '#0f172a';
                        }
                    ]
                }
            },
            legend: { show: false },
            // [Giải thích]: Cấu hình hiển thị đường viền ngăn cách các ô màu trên biểu đồ Heatmap.
            // - show: true (cho phép vẽ đường viền ngăn cách)
            // - width: 2 (độ dày đường viền là 2px)
            // - colors: ['#ffffff'] (màu trắng giúp tách biệt rõ nét giữa các mức độ đóng góp khác nhau)
            stroke: {
                show: true,
                width: 2,
                colors: ['#ffffff']
            },
            xaxis: {
                labels: { 
                    style: { 
                        colors: '#0f172a', 
                        fontWeight: 700,
                        fontSize: '12px'
                    } 
                }
            },
            yaxis: {
                labels: { 
                    style: { 
                        colors: '#0f172a', 
                        fontWeight: 700,
                        fontSize: '12px'
                    } 
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#heatmapChart"), options);
        chart.render();
    } else {
        document.querySelector("#heatmapChart").innerHTML = '<div class="text-center text-muted mt-5"><i class="fas fa-chart-area fa-3x mb-3 text-slate-300"></i><br>Chưa có dữ liệu cho biểu đồ Heatmap.</div>';
    }

});
</script>
@endsection
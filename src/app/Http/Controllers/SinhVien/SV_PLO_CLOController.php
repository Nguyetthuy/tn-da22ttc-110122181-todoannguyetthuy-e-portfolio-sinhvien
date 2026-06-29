<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ThongKeCloSinhVien;
use App\Models\ThongKePloSinhVien;

class SV_PLO_CLOController extends Controller
{
    // Tăng thời gian chạy tối đa lên 300 giây (5 phút)
    public function __construct()
    {
        set_time_limit(300);
    }

    public function index($maSSV = null)
    {
        if ($maSSV) {
            $sinhVien = DB::table('sinh_vien')->where('maSSV', $maSSV)->where('isDelete', false)->first();
            $tenSV = $sinhVien ? ($sinhVien->HoSV . ' ' . $sinhVien->TenSV) : 'Sinh viên';
        } else {
            $tenSV = Session::get('tenSV');
            $maSSV = Session::get('maSSV');
            $sinhVien = DB::table('sinh_vien')->where('maSSV', $maSSV)->where('isDelete', false)->first();
        }
        
        if (!$maSSV) return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại.');

        $lopInfo = null;
        if ($sinhVien) {
            $lopInfo = DB::table('lop_hanh_chinh')
                ->where('maLop', $sinhVien->maLop)
                ->where('isDelete', false)
                ->first();
        }

        // Tự động đồng bộ khi phát hiện dữ liệu trống hoặc thiếu so với CTĐT
        $danhSachHocPhan = DB::table('nhom_lop')
            ->join('sinh_vien_nhom_lop', 'nhom_lop.maNhomLop', '=', 'sinh_vien_nhom_lop.maNhomLop')
            ->join('hoc_phan', 'nhom_lop.maHocPhan', '=', 'hoc_phan.maHocPhan')
            ->where('sinh_vien_nhom_lop.maSSV', $maSSV)
            ->where('sinh_vien_nhom_lop.isDelete', false)
            ->select('nhom_lop.maHKNH', 'nhom_lop.maNhomLop', 'hoc_phan.maHocPhan', 'hoc_phan.tongSoTinChi')
            ->get();

        $actualCount = DB::table('thongke_plo_sinhvien')
            ->where('maSSV', $maSSV)
            ->count();

        $expectedCount = 0;
        if ($lopInfo) {
            $expectedCount = DB::table('nhom_lop')
                ->join('sinh_vien_nhom_lop', 'nhom_lop.maNhomLop', '=', 'sinh_vien_nhom_lop.maNhomLop')
                ->join('hoc_phan', 'nhom_lop.maHocPhan', '=', 'hoc_phan.maHocPhan')
                ->join('hocphan_cdr_hp', 'hoc_phan.maHocPhan', '=', 'hocphan_cdr_hp.maHocPhan')
                ->join('cdr_hocphan_ctdt', 'hocphan_cdr_hp.maCDR_HP', '=', 'cdr_hocphan_ctdt.maCDR_HP')
                ->where('sinh_vien_nhom_lop.maSSV', $maSSV)
                ->where('sinh_vien_nhom_lop.isDelete', false)
                ->where('hocphan_cdr_hp.isDelete', false)
                ->where('cdr_hocphan_ctdt.maCT', $lopInfo->maCT)
                ->where('cdr_hocphan_ctdt.maKhoaTuyenSinh', $lopInfo->maKhoaTuyenSinh)
                ->where('cdr_hocphan_ctdt.isDelete', false)
                ->select('cdr_hocphan_ctdt.maCDR_CTDT', 'hoc_phan.maHocPhan')
                ->distinct()
                ->get()
                ->count();
        }

        if ($actualCount === 0 || ($expectedCount > 0 && $actualCount < $expectedCount)) {
            try {
                $this->dongBoThongKeTatCaMon($maSSV, $danhSachHocPhan);
            } catch (\Exception $e) {
                // Bỏ qua lỗi để không làm sập giao diện hiển thị
            }
        }

        // 1. Lấy dữ liệu tổng hợp PLO (lọc theo đúng chương trình đào tạo và khóa tuyển sinh của sinh viên)
        $queryPLO = DB::table('thongke_plo_sinhvien')
            ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
            ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
            ->leftjoin('ct_dao_tao','cdr_ctdt.maCT', '=', 'ct_dao_tao.maCT')
            ->where('thongke_plo_sinhvien.maSSV', $maSSV)
            ->where('cdr_ctdt.isDelete', false)
            ->where('khoa_tuyensinh_ct_daotao.isDelete', false);

        if ($lopInfo) {
            $queryPLO->where('khoa_tuyensinh_ct_daotao.maCT', $lopInfo->maCT)
                     ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $lopInfo->maKhoaTuyenSinh);
        }

        $thongkePLO = $queryPLO->select(
                'cdr_ctdt.maCDR_CTDT_VB',
                'cdr_ctdt.tenCDR_CTDT',
                'ct_dao_tao.tenCT',
                DB::raw('SUM(CASE WHEN thongke_plo_sinhvien.ty_le_dat >= 40 THEN thongke_plo_sinhvien.ty_le_dong_gop ELSE 0 END) as ty_le_dat_tb')
            )
            ->groupBy('cdr_ctdt.maCDR_CTDT_VB', 'cdr_ctdt.tenCDR_CTDT', 'ct_dao_tao.tenCT')
            ->orderBy('cdr_ctdt.maCDR_CTDT_VB')
            ->get();

        $chartLabelPLO = [];
        $chartDataPLO = [];
        foreach($thongkePLO as $plo){
            $chartLabelPLO[] = $plo->maCDR_CTDT_VB;
            $chartDataPLO[] = round($plo->ty_le_dat_tb, 2);
        }

        /// 2. Lấy dữ liệu chi tiết cho Heatmap và Bảng hiển thị
        $queryChiTiet = DB::table('thongke_plo_sinhvien')
            ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
            ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
            ->join('hoc_phan', 'thongke_plo_sinhvien.maHocPhan', '=', 'hoc_phan.maHocPhan')
            ->where('thongke_plo_sinhvien.maSSV', $maSSV)
            ->where('cdr_ctdt.isDelete', false)
            ->where('khoa_tuyensinh_ct_daotao.isDelete', false);

        if ($lopInfo) {
            $queryChiTiet->where('khoa_tuyensinh_ct_daotao.maCT', $lopInfo->maCT)
                         ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $lopInfo->maKhoaTuyenSinh);
        }

        $chiTietHocPhan = $queryChiTiet->select(
                'cdr_ctdt.maCDR_CTDT_VB',
                'thongke_plo_sinhvien.maHKNH',
                'hoc_phan.tenHocPhan',
                'hoc_phan.tongSoTinChi',
                'thongke_plo_sinhvien.ty_le_dat',
                'thongke_plo_sinhvien.ty_le_dong_gop',
                'thongke_plo_sinhvien.ty_le_dg_hocky'
            )
            ->orderBy('cdr_ctdt.maCDR_CTDT_VB')
            ->orderBy('thongke_plo_sinhvien.maHKNH', 'DESC')
            ->get();

        // Xử lý dữ liệu Heatmap
        $chiTietPLO = [];
        $tongNamHoc = [];
        $tongHocKy = [];
        
        foreach ($chiTietHocPhan as $chitiet) {
            $parts = explode('-', $chitiet->maHKNH, 2);
            $hocKy = $parts[0] ?? 'Chưa rõ';
            $namHoc = $parts[1] ?? 'Chưa rõ';

            $chiTietPLO[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy][] = $chitiet;

            // --- Tính toán gom nhóm theo Năm học ---
            if (!isset($tongNamHoc[$chitiet->maCDR_CTDT_VB][$namHoc])) {
                $tongNamHoc[$chitiet->maCDR_CTDT_VB][$namHoc] = ['tong' => 0, 'count' => 0];
            }
            $tongNamHoc[$chitiet->maCDR_CTDT_VB][$namHoc]['tong'] += $chitiet->ty_le_dat;
            $tongNamHoc[$chitiet->maCDR_CTDT_VB][$namHoc]['count'] += 1;
            
            // --- Tính toán gom nhóm theo Học kỳ ---
            if (!isset($tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy])) {
                $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy] = [
                    'tong' => 0, 
                    'count' => 0, 
                    'tongDongGop' => 0,
                    'tongDgHocky' => 0 
                ];
            }
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['tong'] += $chitiet->ty_le_dat;
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['count'] += 1;
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['tongDongGop'] += ($chitiet->ty_le_dong_gop ?? 0);
            
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['tongDgHocky'] = ($chitiet->ty_le_dg_hocky ?? 0);
        }

        // Sắp xếp mảng
        foreach ($chiTietPLO as $ploCode => &$namHocs) {
            uksort($namHocs, function($a, $b) { return strcmp($a, $b); });
            foreach ($namHocs as $namHoc => &$hocKys) {
                uksort($hocKys, function($a, $b) { return strcmp($a, $b); });
            }
        }
        unset($namHocs, $hocKys);

        $diemNamHoc = [];
        foreach ($tongNamHoc as $plo => $namHocs) {
            foreach ($namHocs as $namHoc => $data) {
                $diemNamHoc[$plo][$namHoc] = $data['count'] > 0 ? $data['tong'] / $data['count'] : 0;
            }
        }

        $diemHocKy = [];
        $dongGopHocKy = [];
        foreach ($tongHocKy as $plo => $namHocs) {
            foreach ($namHocs as $namHoc => $hocKys) {
                foreach ($hocKys as $hocKy => $data) {
                    $diemHocKy[$plo][$namHoc][$hocKy] = $data['count'] > 0 ? $data['tong'] / $data['count'] : 0;
                    $dongGopHocKy[$plo][$namHoc][$hocKy] = $data['tongDgHocky']; // Đồng bộ lấy giá trị học kỳ
                }
            }
        }

        $heatmapDataRaw = [];
        $allSemesters = [];
        foreach ($chiTietHocPhan as $row) {
            if (!in_array($row->maHKNH, $allSemesters)) $allSemesters[] = $row->maHKNH;
            $heatmapDataRaw[$row->maCDR_CTDT_VB][$row->maHKNH] = $row->ty_le_dg_hocky ?? 0;
        }

        usort($allSemesters, function($a, $b) {
            $partsA = explode('-', $a); $partsB = explode('-', $b);
            $namA = $partsA[1] ?? ''; $namB = $partsB[1] ?? '';
            if ($namA === $namB) return strcmp($partsA[0], $partsB[0]);
            return strcmp($namA, $namB);
        });

        $allPloCodesForHeatmap = $thongkePLO->pluck('maCDR_CTDT_VB')->toArray();
        $heatmapSeries = [];
        foreach ($allSemesters as $sem) {
            $dataPoints = [];
            foreach ($allPloCodesForHeatmap as $ploCode) {
                $dataPoints[] = [
                    'x' => $ploCode,
                    'y' => isset($heatmapDataRaw[$ploCode][$sem]) ? round($heatmapDataRaw[$ploCode][$sem], 2) : 0
                ];
            }
            $heatmapSeries[] = ['name' => $sem, 'data' => $dataPoints];
        }

        return view('sinhvien.chuongtrinhdaotao.chuandaura', compact('tenSV', 'chartLabelPLO', 'chartDataPLO','thongkePLO', 'chiTietPLO', 'diemNamHoc', 'diemHocKy', 'dongGopHocKy', 'heatmapSeries'));
    }

    public function capNhatData(Request $request)
    {
        $maSSV = Session::get('maSSV');
        if (!$maSSV) return back()->with('error', 'Phiên đăng nhập hết hạn.');

        // 🌟 TỐI ƯU: Đã lấy kèm 'hoc_phan.tongSoTinChi' từ câu lệnh JOIN này
        $danhSachHocPhan = DB::table('nhom_lop')
            ->join('sinh_vien_nhom_lop', 'nhom_lop.maNhomLop', '=', 'sinh_vien_nhom_lop.maNhomLop')
            ->join('hoc_phan', 'nhom_lop.maHocPhan', '=', 'hoc_phan.maHocPhan')
            ->where('sinh_vien_nhom_lop.maSSV', $maSSV)
            ->where('sinh_vien_nhom_lop.isDelete', false)
            ->select('nhom_lop.maHKNH', 'nhom_lop.maNhomLop', 'hoc_phan.maHocPhan', 'hoc_phan.tongSoTinChi')
            ->get();
            
        try {
            $this->dongBoThongKeTatCaMon($maSSV, $danhSachHocPhan);
            return back()->with('success', 'Cập nhật dữ liệu Chuẩn đầu ra thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
    
    // --- HÀM ĐƯỢC TỐI ƯU HÓA TOÀN DIỆN ---
    public function dongBoThongKeTatCaMon($maSSV, $danhSachHocPhan)
    {
        $kqController = new \App\Http\Controllers\SinhVien\SV_KQCDRController();
        
        $sinhVien = DB::table('sinh_vien')->where('maSSV', $maSSV)->where('isDelete', false)->first();
        $lopInfo = null;
        if ($sinhVien) {
            $lopInfo = DB::table('lop_hanh_chinh')
                ->where('maLop', $sinhVien->maLop)
                ->where('isDelete', false)
                ->first();
        }

        // Bọc quy trình vào một Transaction để tăng tốc ghi và bảo mật dữ liệu
        DB::transaction(function () use ($maSSV, $danhSachHocPhan, $kqController, $lopInfo) {
            // 1. Xóa dữ liệu thống kê cũ (Đã loại bỏ thongke_clo_sinhvien)
            DB::table('thongke_plo_sinhvien')->where('maSSV', $maSSV)->delete();

            $allCLOData = [];  
            $allPLOData = [];  
            $monDaTinhPlo = []; 
            $assessedCourses = []; // Theo dõi các học phần thực sự có dữ liệu đánh giá trong bất kỳ nhóm lớp nào

            // 2. Duyệt qua từng nhóm lớp để lấy điểm chuẩn đầu ra
            foreach ($danhSachHocPhan as $hp) {
                $maHocPhan = $hp->maHocPhan;
                $maNhomLop = $hp->maNhomLop;
                $maHKNH    = $hp->maHKNH;
                // 🌟 TỐI ƯU: Đọc trực tiếp biến từ danh sách đã lấy ở hàm capNhatData, KHÔNG TRUY VẤN LẠI DB
                $tinChiMonHoc = $hp->tongSoTinChi ?? 0;

                // --- ĐÃ LOẠI BỎ XỬ LÝ GOM DỮ LIỆU CLO ---

                // --- XỬ LÝ GOM DỮ LIỆU PLO THÔ ---
                $dataPLO = $kqController->get_CDR_CTDT_Data($maHocPhan, $maNhomLop, $maSSV);
                if (is_array($dataPLO)) {
                    foreach ($dataPLO as $row) {
                        if (empty($row) || !isset($row[0])) continue;
                        $maCDR   = $row[0];
                        $diem    = $row[3] ?? 0;
                        $trongSo = $row[4] ?? 1;
                        
                        if (!isset($allPLOData[$maCDR][$maHocPhan])) {
                            $allPLOData[$maCDR][$maHocPhan] = [
                                'maHKNH' => $maHKNH, 
                                'diem' => 0, 
                                'trongso' => 0, 
                                'tinChi' => $tinChiMonHoc
                            ];
                        }
                        $allPLOData[$maCDR][$maHocPhan]['diem']    += ($diem * $trongSo);
                        $allPLOData[$maCDR][$maHocPhan]['trongso'] += $trongSo;

                        if (!isset($monDaTinhPlo[$maCDR][$maHKNH][$maHocPhan])) {
                            $monDaTinhPlo[$maCDR][$maHKNH][$maHocPhan] = $tinChiMonHoc;
                        }
                        
                        // Đánh dấu môn học này đã có dữ liệu đánh giá thực tế
                        $assessedCourses[$maHocPhan] = true;
                    }
                }
            }

            // Giai đoạn 2: Bổ sung các môn học hoàn toàn chưa có bất kỳ dữ liệu đánh giá nào (0đ)
            $uniqueRegisteredCourses = [];
            foreach ($danhSachHocPhan as $hp) {
                $uniqueRegisteredCourses[$hp->maHocPhan] = $hp;
            }

            foreach ($uniqueRegisteredCourses as $maHocPhan => $hp) {
                if (!isset($assessedCourses[$maHocPhan])) {
                    $maHKNH = $hp->maHKNH;
                    $tinChiMonHoc = $hp->tongSoTinChi ?? 0;

                    // Lấy danh sách các PLO được ánh xạ cho học phần này trong CTĐT của sinh viên
                    $mappedPLOs = [];
                    if ($lopInfo) {
                        $mappedPLOs = DB::table('hocphan_cdr_hp')
                            ->join('cdr_hocphan_ctdt', 'hocphan_cdr_hp.maCDR_HP', '=', 'cdr_hocphan_ctdt.maCDR_HP')
                            ->where('hocphan_cdr_hp.maHocPhan', $maHocPhan)
                            ->where('hocphan_cdr_hp.isDelete', false)
                            ->where('cdr_hocphan_ctdt.maCT', $lopInfo->maCT)
                            ->where('cdr_hocphan_ctdt.maKhoaTuyenSinh', $lopInfo->maKhoaTuyenSinh)
                            ->where('cdr_hocphan_ctdt.isDelete', false)
                            ->pluck('cdr_hocphan_ctdt.maCDR_CTDT')
                            ->unique()
                            ->toArray();
                    }

                    foreach ($mappedPLOs as $maCDR) {
                        if (!isset($allPLOData[$maCDR][$maHocPhan])) {
                            $allPLOData[$maCDR][$maHocPhan] = [
                                'maHKNH' => $maHKNH, 
                                'diem' => 0, 
                                'trongso' => 1, // Trọng số = 1 để tránh lỗi chia cho 0
                                'tinChi' => $tinChiMonHoc
                            ];
                        }
                        if (!isset($monDaTinhPlo[$maCDR][$maHKNH][$maHocPhan])) {
                            $monDaTinhPlo[$maCDR][$maHKNH][$maHocPhan] = $tinChiMonHoc;
                        }
                    }
                }
            }

            // 3. ĐÃ LOẠI BỎ BULK INSERT CLO

            // 4. Tính toán ma trận tín chỉ cấp PLO và Học Kỳ tương ứng
            $tongTinChiPLO_Map = []; 
            $tongTinChiHK_Map  = []; 

            foreach ($monDaTinhPlo as $maCDR => $cacHocKy) {
                $tongPLO = 0;
                foreach ($cacHocKy as $maHKNH => $cacMon) {
                    $tongHK = array_sum($cacMon); 
                    $tongTinChiHK_Map[$maCDR][$maHKNH] = $tongHK;
                    $tongPLO += $tongHK;
                }
                $tongTinChiPLO_Map[$maCDR] = $tongPLO;
            }

            // 5. BULK INSERT PLO
            $ploInserts = [];
            foreach ($allPLOData as $maCDR => $mons) {
                $tongTC_PLO = $tongTinChiPLO_Map[$maCDR] ?? 0;
                
                foreach ($mons as $maHocPhan => $val) {
                    if ($val['trongso'] > 0) {
                        $maHKNH    = $val['maHKNH'];
                        $tongTC_HK = $tongTinChiHK_Map[$maCDR][$maHKNH] ?? 0;
                        
                        $ty_le   = min(100, ($val['diem'] / $val['trongso']));
                        $dongGop = ($tongTC_PLO > 0 && $val['tinChi'] > 0) ? ($val['tinChi'] / $tongTC_PLO) * 100 : 0;
                        $dongGopHK = ($tongTC_PLO > 0 && $tongTC_HK > 0) ? ($tongTC_HK / $tongTC_PLO) * 100 : 0;
                        
                        $ploInserts[] = [
                            'maSSV' => $maSSV, 
                            'maHocPhan' => $maHocPhan, 
                            'maHKNH' => $maHKNH,
                            'maCDR_CTDT' => $maCDR, 
                            'ty_le_dat' => round($ty_le, 2),
                            'ty_le_dong_gop' => round($dongGop, 2),
                            'ty_le_dg_hocky' => round($dongGopHK, 2), 
                            'created_at' => now(), 
                            'updated_at' => now()
                        ];
                    }
                }
            }

            if (!empty($ploInserts)) {
                foreach (array_chunk($ploInserts, 500) as $chunk) {
                    DB::table('thongke_plo_sinhvien')->insert($chunk);
                }
            }
        });
    }
}
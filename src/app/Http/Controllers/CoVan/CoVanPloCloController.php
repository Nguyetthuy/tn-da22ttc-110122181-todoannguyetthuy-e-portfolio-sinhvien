<?php

namespace App\Http\Controllers\CoVan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
// Hãy chắc chắn class này tồn tại và chứa hàm xử lý cho Cố vấn
use App\Http\Controllers\SinhVien\SV_CDRCoVanController; 

class CoVanPloCloController extends Controller
{
    public function __construct() 
    { 
        set_time_limit(300); 
    }

    public function xemPloCloSinhVien($maSSV)
    {
        // 1. Lấy thông tin sinh viên
        $sinhVien = DB::table('sinh_vien')->where('maSSV', $maSSV)->where('isDelete', false)->first();
        $tenSV = $sinhVien ? ($sinhVien->HoSV . ' ' . $sinhVien->TenSV) : 'Sinh viên';

        // Tự động đồng bộ nếu thiếu dữ liệu
        if ($sinhVien) {
            $lopInfo = DB::table('lop_hanh_chinh')
                ->where('maLop', $sinhVien->maLop)
                ->where('isDelete', false)
                ->first();

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
                    $controller = new SV_CDRCoVanController();
                    $controller->get_CDR_CTDT_Data_ChoCoVan($maSSV);
                } catch (\Exception $e) {
                    // Bỏ qua lỗi hiển thị
                }
            }
        }

        // 2. Lấy dữ liệu tổng hợp PLO (vẽ biểu đồ cột và lặp hàng chính)
        $thongkePLO = DB::table('thongke_plo_sinhvien')
            ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
            ->leftJoin('ct_dao_tao', 'cdr_ctdt.maCT', '=', 'ct_dao_tao.maCT')
            ->where('thongke_plo_sinhvien.maSSV', $maSSV)
            ->select(
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
        foreach ($thongkePLO as $plo) {
            $chartLabelPLO[] = $plo->maCDR_CTDT_VB;
            $chartDataPLO[] = round($plo->ty_le_dat_tb, 2);
        }

        // 3. Lấy dữ liệu chi tiết cho bảng phân tầng và Heatmap (Đã cập nhật select thêm tỷ lệ HK)
        $chiTietHocPhan = DB::table('thongke_plo_sinhvien')
            ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
            ->join('hoc_phan', 'thongke_plo_sinhvien.maHocPhan', '=', 'hoc_phan.maHocPhan')
            ->where('thongke_plo_sinhvien.maSSV', $maSSV)
            ->select(
                'cdr_ctdt.maCDR_CTDT_VB',
                'thongke_plo_sinhvien.maHKNH',
                'hoc_phan.maHocPhan',
                'hoc_phan.tenHocPhan',
                'hoc_phan.tongSoTinChi',
                // Lấy trung bình cộng tỷ lệ đạt của môn học nếu môn đó có nhiều đầu điểm cấu phần
                DB::raw('AVG(thongke_plo_sinhvien.ty_le_dat) as ty_le_dat'),
                // Lấy tổng tỷ lệ đóng góp của môn đó vào PLO hiện tại
                DB::raw('SUM(thongke_plo_sinhvien.ty_le_dong_gop) as ty_le_dong_gop'),
                // 🌟 MỚI: Lấy giá trị MAX/AVG của tỷ lệ học kỳ vì các dòng cùng kỳ sẽ chung một giá trị giống nhau
                DB::raw('MAX(thongke_plo_sinhvien.ty_le_dg_hocky) as ty_le_dg_hocky')
            )
            ->groupBy(
                'cdr_ctdt.maCDR_CTDT_VB',
                'thongke_plo_sinhvien.maHKNH',
                'hoc_phan.maHocPhan',
                'hoc_phan.tenHocPhan',
                'hoc_phan.tongSoTinChi'
            )
            ->orderBy('cdr_ctdt.maCDR_CTDT_VB')
            ->orderBy('thongke_plo_sinhvien.maHKNH', 'DESC')
            ->get();

        // 4. Xử lý chia mảng cấu trúc dữ liệu học kỳ
        $chiTietPLO = [];
        $tongNamHoc = [];
        $tongHocKy = [];
        $heatmapDataRaw = [];
        $allSemesters = [];

        foreach ($chiTietHocPhan as $chitiet) {
            $parts = explode('-', $chitiet->maHKNH, 2);
            $hocKy = $parts[0] ?? 'Chưa rõ';
            $namHoc = $parts[1] ?? 'Chưa rõ';

            $chiTietPLO[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy][] = $chitiet;

            if (!in_array($chitiet->maHKNH, $allSemesters)) {
                $allSemesters[] = $chitiet->maHKNH;
            }
            
            // 🌟 CẬP NHẬT: Gán trực tiếp giá trị tỷ lệ đóng góp học kỳ thay vì cộng dồn (+=)
            $heatmapDataRaw[$chitiet->maCDR_CTDT_VB][$chitiet->maHKNH] = $chitiet->ty_le_dg_hocky ?? 0;

            if (!isset($tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy])) {
                $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy] = [
                    'tong' => 0, 
                    'count' => 0, 
                    'tongDongGop' => 0,
                    'tongDgHocky' => 0 // 🌟 Khởi tạo biến lưu ra View
                ];
            }
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['tong'] += $chitiet->ty_le_dat;
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['count'] += 1;
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['tongDongGop'] += ($chitiet->ty_le_dong_gop ?? 0);
            
            // 🌟 CẬP NHẬT: Gán giá trị không lũy tiến phục vụ hiển thị mảng ngoài giao diện view
            $tongHocKy[$chitiet->maCDR_CTDT_VB][$namHoc][$hocKy]['tongDgHocky'] = $chitiet->ty_le_dg_hocky ?? 0;
        }

        // Sắp xếp mảng theo thứ tự thời gian tăng dần
        foreach ($chiTietPLO as $ploCode => &$namHocs) {
            uksort($namHocs, function($a, $b) { return strcmp($a, $b); });
            foreach ($namHocs as $namHoc => &$hocKys) {
                uksort($hocKys, function($a, $b) { return strcmp($a, $b); });
            }
        }
        unset($namHocs, $hocKys);

        $diemHocKy = [];
        $dongGopHocKy = [];
        foreach ($tongHocKy as $plo => $namHocs) {
            foreach ($namHocs as $namHoc => $hocKys) {
                foreach ($hocKys as $hocKy => $data) {
                    $diemHocKy[$plo][$namHoc][$hocKy] = $data['count'] > 0 ? $data['tong'] / $data['count'] : 0;
                    // 🌟 CẬP NHẬT: Đổi biến $dongGopHocKy hứng giá trị tổng học kỳ chuẩn thay vì tổng đóng góp môn học
                    $dongGopHocKy[$plo][$namHoc][$hocKy] = $data['tongDgHocky'];
                }
            }
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

        return view('covan.chuandaurasv', compact(
            'maSSV', 'sinhVien', 'thongkePLO', 'chiTietPLO', 
            'diemHocKy', 'dongGopHocKy', 'chartLabelPLO', 
            'chartDataPLO', 'heatmapSeries'
        ));
    }

    public function capNhatDaTaCoVan(Request $request)
    {
        $maSSV = $request->input('maSSV');
        if (!$maSSV) return back()->with('warning', 'Thiếu mã sinh viên');

        try {
            if (!class_exists(SV_CDRCoVanController::class)) {
                throw new \Exception("Hệ thống chưa cấu hình bộ tính toán điểm cho Cố vấn (Class không tồn tại).");
            }

            $controller = new SV_CDRCoVanController();
            $controller->get_CDR_CTDT_Data_ChoCoVan($maSSV);

            return redirect()->route('covan.chuandaurasv', ['maSSV' => $maSSV])
                             ->with('success', 'Đã đồng bộ dữ liệu thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function dongBoCaLop($maLop)
    {
        $maGV = Session::get('maGV');
        $checkCoVan = DB::table('co_van_lop')
            ->where('maGV', $maGV)
            ->where('maLop', $maLop)
            ->where('isDelete', false)
            ->where(function ($q) {
                $q->whereNull('ngayKetThuc')
                  ->orWhere('ngayKetThuc', '>=', now()->toDateString());
            })
            ->exists();

        if (!$checkCoVan) {
            abort(403, 'Bạn không có quyền quản lý lớp học này.');
        }

        $students = DB::table('sinh_vien')
            ->where('maLop', $maLop)
            ->where('isDelete', false)
            ->pluck('maSSV');

        if ($students->isEmpty()) {
            return back()->with('warning', 'Lớp học này hiện tại chưa có sinh viên nào.');
        }

        try {
            if (!class_exists(SV_CDRCoVanController::class)) {
                throw new \Exception("Hệ thống chưa cấu hình bộ tính toán điểm cho Cố vấn (Class không tồn tại).");
            }

            $controller = new SV_CDRCoVanController();
            foreach ($students as $maSSV) {
                $controller->get_CDR_CTDT_Data_ChoCoVan($maSSV);
            }

            return back()->with('success', 'Đồng bộ dữ liệu chuẩn đầu ra toàn bộ lớp thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi hệ thống khi đồng bộ lớp: ' . $e->getMessage());
        }
    }
}
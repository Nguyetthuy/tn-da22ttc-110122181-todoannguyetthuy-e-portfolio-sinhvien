<?php

namespace App\Http\Controllers\sinhvien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SVHomeController extends Controller
{
    public function index()
    {
        $maSSV = Session::get('maSSV');
        if (!$maSSV) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại.');
        }

        // 1. Lấy thông tin cá nhân của sinh viên
        $svInfo = DB::table('sinh_vien')
            ->join('lop_hanh_chinh', 'sinh_vien.maLop', '=', 'lop_hanh_chinh.maLop')
            ->leftJoin('ct_dao_tao', 'lop_hanh_chinh.maCT', '=', 'ct_dao_tao.maCT')
            ->leftJoin('khoa_tuyensinh', 'lop_hanh_chinh.maKhoaTuyenSinh', '=', 'khoa_tuyensinh.maKhoaTuyenSinh')
            ->where('sinh_vien.maSSV', $maSSV)
            ->where('sinh_vien.isDelete', false)
            ->select('sinh_vien.*', 'lop_hanh_chinh.tenLop', 'lop_hanh_chinh.maCT', 'lop_hanh_chinh.maKhoaTuyenSinh', 'ct_dao_tao.tenCT', 'khoa_tuyensinh.namTS')
            ->first();

        if (!$svInfo) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên!');
        }

        // 2. Lấy thông tin cố vấn học tập của lớp
        $coVan = DB::table('co_van_lop')
            ->join('giang_vien', 'co_van_lop.maGV', '=', 'giang_vien.maGV')
            ->where('co_van_lop.maLop', $svInfo->maLop)
            ->where('co_van_lop.isDelete', false)
            ->where(function ($q) {
                $q->whereNull('co_van_lop.ngayKetThuc')
                  ->orWhere('co_van_lop.ngayKetThuc', '>=', now()->toDateString());
            })
            ->select('giang_vien.hoGV', 'giang_vien.tenGV')
            ->first();
        $tenCoVan = $coVan ? $coVan->hoGV . ' ' . $coVan->tenGV : 'Chưa phân công';

        // 3. Đếm số học phần thuộc lớp hành chính
        $countHocPhan = DB::table('giangday')
            ->where('maLop', $svInfo->maLop)
            ->where('isDelete', false)
            ->distinct('maHocPhan')
            ->count('maHocPhan');

        // 4. Đếm số học kỳ
        $countHocKy = DB::table('giangday')
            ->where('maLop', $svInfo->maLop)
            ->where('isDelete', false)
            ->distinct('maHKNH')
            ->count('maHKNH');

        // 5. Đếm số lượng chuẩn đầu ra CTĐT (PLO) đã được gán thực tế cho khóa tuyển sinh (loại bỏ trùng lặp)
        $countPLO = DB::table('cdr_ctdt')
            ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
            ->where('khoa_tuyensinh_ct_daotao.maCT', $svInfo->maCT)
            ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $svInfo->maKhoaTuyenSinh)
            ->where('cdr_ctdt.isDelete', false)
            ->where('khoa_tuyensinh_ct_daotao.isDelete', false)
            ->distinct('cdr_ctdt.maCDR_CTDT_VB')
            ->count('cdr_ctdt.maCDR_CTDT_VB');

        // 6. Số lượng chuẩn đầu ra CLO đã đạt của sinh viên (đã loại bỏ thongke_clo_sinhvien)
        $countCLODat = 0;


        // 7. Số lượng chuẩn đầu ra PLO đã đạt của sinh viên (loại bỏ trùng lặp, lọc theo khóa tuyển sinh và tính theo mã VB)
        // [Giải thích]: Thực hiện đếm số lượng các PLO mà sinh viên đã tích lũy đạt chuẩn đầu ra.
        // - Một PLO được xem là ĐẠT nếu tổng tỷ lệ phần trăm đóng góp (ty_le_dong_gop) của tất cả các môn học đã vượt qua 
        //   (có tỷ lệ đạt của môn đó ty_le_dat >= 40%) tích lũy lại lớn hơn hoặc bằng 70% (mức độ hoàn thành >= 70%).
        // - Kết quả trả về: Một số nguyên biểu thị số lượng PLO đạt chuẩn đầu ra (ví dụ: 10).
        $countPLODat = DB::table('thongke_plo_sinhvien')
            ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
            ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
            ->where('thongke_plo_sinhvien.maSSV', $maSSV)
            ->where('khoa_tuyensinh_ct_daotao.maCT', $svInfo->maCT)
            ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $svInfo->maKhoaTuyenSinh)
            ->where('khoa_tuyensinh_ct_daotao.isDelete', false)
            ->where('cdr_ctdt.isDelete', false)
            ->groupBy('cdr_ctdt.maCDR_CTDT_VB')
            ->having(DB::raw('SUM(CASE WHEN thongke_plo_sinhvien.ty_le_dat >= 40 THEN thongke_plo_sinhvien.ty_le_dong_gop ELSE 0 END)'), '>=', 70)
            ->get()
            ->count();

        return view('sinhvien.home', [
            'svInfo' => $svInfo,
            'tenCoVan' => $tenCoVan,
            'countHocPhan' => $countHocPhan,
            'countHocKy' => $countHocKy,
            'countPLO' => $countPLO,
            'countCLODat' => $countCLODat,
            'countPLODat' => $countPLODat
        ]);
    }
}

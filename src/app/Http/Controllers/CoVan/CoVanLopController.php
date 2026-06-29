<?php

namespace App\Http\Controllers\CoVan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\coVanLop;
use App\Models\lopHanhChinh;
use App\Models\sinhVien;
use App\Models\hocPhan;
use App\Models\giangVien;
use App\Models\giangDay;

class CoVanLopController extends Controller
{
    public function index()
    {
        $maGV = Session::get('maGV');

        // Lấy danh sách lớp đang phụ trách (chưa kết thúc hoặc chưa có ngày kết thúc)
        $dsLop = DB::table('co_van_lop')
            ->join('lop_hanh_chinh', 'co_van_lop.maLop', '=', 'lop_hanh_chinh.maLop')
            ->leftJoin('ct_dao_tao', 'lop_hanh_chinh.maCT', '=', 'ct_dao_tao.maCT')
            ->where('co_van_lop.maGV', $maGV)
            ->where('co_van_lop.isDelete', false)
            ->where(function ($q) {
                $q->whereNull('co_van_lop.ngayKetThuc')
                  ->orWhere('co_van_lop.ngayKetThuc', '>=', now()->toDateString());
            })
            ->select(
                'lop_hanh_chinh.maLop',
                'lop_hanh_chinh.tenLop',
                'ct_dao_tao.tenCT',
                'co_van_lop.ngayBatDau',
                'co_van_lop.ngayKetThuc',
                DB::raw('(SELECT COUNT(*) FROM sinh_vien WHERE sinh_vien.maLop = lop_hanh_chinh.maLop AND sinh_vien.isDelete = false) as soSinhVien')
            )
            ->get();

        return view('covan.lop.index', compact('dsLop'));
    }

    public function danhSachSinhVien($maLop)
    {
        // Kiểm tra cố vấn có quyền xem lớp này không
        if (!$this->checkCoVan($maLop)) {
            abort(403, 'Bạn không có quyền xem thông tin lớp học này.');
        }

        // Lấy thông tin lớp
        $lop = DB::table('lop_hanh_chinh')
            ->leftJoin('ct_dao_tao', 'lop_hanh_chinh.maCT', '=', 'ct_dao_tao.maCT')
            ->where('lop_hanh_chinh.maLop', $maLop)
            ->where('lop_hanh_chinh.isDelete', false)
            ->first();

        // Lấy danh sách sinh viên thuộc lớp này
        $dsSinhVien = DB::table('sinh_vien')
            ->where('maLop', $maLop)
            ->where('isDelete', false)
            ->orderBy('TenSV')
            ->get();

        return view('covan.sinhvien', compact('lop', 'dsSinhVien'));    
    }

    // Kiểm tra quyền cố vấn quản lý lớp học
    private function checkCoVan($maLop)
    {
        $maGV = Session::get('maGV');
        
        return DB::table('co_van_lop')
            ->where('maGV', $maGV)
            ->where('maLop', $maLop)
            ->where('isDelete', false)
            ->where(function ($q) {
                $q->whereNull('ngayKetThuc')
                  ->orWhere('ngayKetThuc', '>=', now()->toDateString());
            })
            ->exists();
    }

    // Kiểm tra kết quả học tập các học phần của sinh viên
    public function danhSachHocPhan($maSSV)
    {
        // 0. Tìm sinh viên để lấy ra mã lớp thực tế
        $sinhVienCheck = DB::table('sinh_vien')
            ->where('maSSV', $maSSV)
            ->where('isDelete', false)
            ->first();

        if (!$sinhVienCheck) {
            abort(404, 'Không tìm thấy sinh viên này.');
        }

        $maLop = $sinhVienCheck->maLop; 

        // 1. Kiểm tra bảo mật quyền của cố vấn
        if (!$this->checkCoVan($maLop)) {
            abort(403, 'Bạn không có quyền xem thông tin lớp học này.');
        }

        // 2. Lấy dữ liệu học phần dựa trên lớp học và sinh viên (Đã fix lỗi cú pháp JOIN Alias)
        $sv = DB::table('sinh_vien')
            ->where('sinh_vien.isDelete', false)
            ->where('sinh_vien.maSSV', $maSSV)
            ->join('lop_hanh_chinh', function ($a) {
                $a->on('lop_hanh_chinh.maLop', '=', 'sinh_vien.maLop')
                  ->where('lop_hanh_chinh.isDelete', false);
            })
            ->join('giangday', function ($q) {
                $q->on('giangday.maLop', '=', 'lop_hanh_chinh.maLop')
                  ->where('giangday.isDelete', false);
            })
            ->join('hoc_phan', function ($x) { 
                // Sử dụng lại biến $q để tránh lỗi không tìm thấy table giang_day ngoài scope
                $x->on('hoc_phan.maHocPhan', '=', 'giangday.maHocPhan')
                  ->where('hoc_phan.isDelete', false);
            })
            ->select([
                'hoc_phan.maHocPhan',
                'hoc_phan.tenHocPhan',
                'hoc_phan.tongSoTinChi',
                'giangday.maHKNH',
                'giangday.maLop',
                'sinh_vien.maSSV'
            ])
            ->get();

        // Lấy danh sách giảng viên của từng học phần
        $gd_data = giangDay::where('isDelete', false)->get();
        $hp = hocPhan::where('isDelete', false)->get();
        $giangvien = giangVien::where('isDelete', false)->get();
        $lop = lopHanhChinh::where('isDelete', false)->get();

        foreach ($sv as $x) {
            $gv = [];
            foreach ($gd_data as $y) {
                if (
                    $y->maHocPhan == $x->maHocPhan &&
                    $y->maHKNH == $x->maHKNH &&
                    $y->maLop == $x->maLop
                ) {
                    if (!in_array($y->maGV, $gv)) {
                        $gv[] = $y->maGV;
                    }
                }
            }

            $temp = [];
            foreach ($gv as $t) {
                $temp_gv = giangVien::where('isDelete', false)
                    ->where('maGV', $t)
                    ->first();

                if ($temp_gv) {
                    $temp[] = $temp_gv;
                }
            }
            $x->GV = $temp;
        }

        // 4. TRẢ VỀ VIEW
        return view('covan.hocphansv', [
            'giangday' => $sv,     
            'sinhVien' => $sinhVienCheck, 
            'hocphan' => $hp,      
            'giangvien' => $giangvien, 
            'lop' => $lop          
        ]);
    }

    // Xem chi tiết điểm một học phần của sinh viên
    public function xemDiem($maSSV, $maHocPhan, $maLop)
    {
        // Bảo mật: Kiểm tra xem cố vấn này có thực sự quản lý lớp học này không
        if (!$this->checkCoVan($maLop)) {
            abort(403, 'Bạn không có quyền xem thông tin điểm của lớp học này.');
        }

        // 1. Kiểm tra sinh viên tồn tại
        $sinhVien = DB::table('sinh_vien')
            ->where('maSSV', $maSSV)
            ->where('isDelete', false)
            ->first();

        if (!$sinhVien) {
            abort(404, 'Không tìm thấy thông tin sinh viên.');
        }

        // 2. Lấy thông tin học phần
        $hocPhan = DB::table('hoc_phan')
            ->where('maHocPhan', $maHocPhan)
            ->where('isDelete', false)
            ->first(['maHocPhan', 'maHocPhan_VB', 'tenHocPhan']);

        // 3. Lấy nhóm lớp học phần của sinh viên
        $nhomLop = DB::table('nhom_lop')
            ->join('sinh_vien_nhom_lop', 'nhom_lop.maNhomLop', '=', 'sinh_vien_nhom_lop.maNhomLop')
            ->where('nhom_lop.maHocPhan', $maHocPhan)
            ->where('nhom_lop.maLop', $maLop)
            ->where('sinh_vien_nhom_lop.maSSV', $maSSV) 
            ->where('nhom_lop.isDelete', false)
            ->where('sinh_vien_nhom_lop.isDelete', false)
            ->first(['nhom_lop.maNhomLop', 'nhom_lop.maHKNH']);

        $maNhomLop = $nhomLop ? $nhomLop->maNhomLop : null;
        $maHKNH    = $nhomLop ? $nhomLop->maHKNH : null;

        // 4. Lấy hình thức đánh giá (Trọng số cột điểm)
        $hinhthucdg = DB::select("
            SELECT hocphan_hinhthuc_dg.id, hocphan_hinhthuc_dg.trongSo,
                   hocphan_hinhthuc_dg.maLoaiDG, loai_danh_gia.tenLoaiDG
            FROM hocphan_hinhthuc_dg, loai_danh_gia
            WHERE hocphan_hinhthuc_dg.maHocPhan = ?
            AND hocphan_hinhthuc_dg.isDelete = false
            AND loai_danh_gia.maLoaiDG = hocphan_hinhthuc_dg.maLoaiDG
            ORDER BY hocphan_hinhthuc_dg.id
        ", [$maHocPhan]);

        // 5. Lấy điểm chi tiết từ phiếu chấm
        $diem_pc = [];
        if ($maNhomLop) {
            $diem_pc = DB::select("
                SELECT phieu_cham.diemSo, hocphan_hinhthuc_dg.maLoaiDG, hocphan_hinhthuc_dg.trongSo
                FROM phieu_cham, de_thi, hocphan_hinhthuc_dg
                WHERE phieu_cham.maSSV = ?
                AND phieu_cham.maDe = de_thi.maDe
                AND de_thi.maNhomLop = ?
                AND de_thi.id_hocphan_hinhthucdg = hocphan_hinhthuc_dg.id
                AND hocphan_hinhthuc_dg.maHocPhan = ?
                AND phieu_cham.trangThai = 1
                AND de_thi.isDelete = false
            ", [$maSSV, $maNhomLop, $maHocPhan]);
        }

        // 6. Trả về View dành cho Cố vấn hiển thị bảng điểm
        return view('covan.diemsv', [
            'hocPhan'    => $hocPhan,
            'hinhthucdg' => $hinhthucdg,
            'diem_pc'    => $diem_pc,
            'maHKNH'     => $maHKNH,
            'sv'         => $sinhVien, 
            'maLop'      => $maLop,    
        ]);
    }

    
    
}
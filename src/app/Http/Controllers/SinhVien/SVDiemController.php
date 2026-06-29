<?php

namespace App\Http\Controllers\sinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SVDiemController extends Controller
{
    public function index($maHocPhan, $maLop)
    {
        $maSSV = Session::get('maSSV');

        // Lấy thông tin học phần
        $hocPhan = DB::table('hoc_phan')
            ->where('maHocPhan', $maHocPhan)
            ->where('isDelete', false)
            ->first(['maHocPhan', 'maHocPhan_VB', 'tenHocPhan']);

        // Lấy nhóm lớp của SV
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

        // Lấy hình thức đánh giá
        $hinhthucdg = DB::select("
            SELECT hocphan_hinhthuc_dg.id, hocphan_hinhthuc_dg.trongSo,
                   hocphan_hinhthuc_dg.maLoaiDG, loai_danh_gia.tenLoaiDG
            FROM hocphan_hinhthuc_dg, loai_danh_gia
            WHERE hocphan_hinhthuc_dg.maHocPhan = ?
            AND hocphan_hinhthuc_dg.isDelete = false
            AND loai_danh_gia.maLoaiDG = hocphan_hinhthuc_dg.maLoaiDG
            ORDER BY hocphan_hinhthuc_dg.id
        ", [$maHocPhan]);

        // Lấy điểm phiếu chấm của SV
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

        $sv = DB::table('sinh_vien')->where('maSSV', $maSSV)->first();

        return view('sinhvien.hocphan.diem', [
            'hocPhan'    => $hocPhan,
            'hinhthucdg' => $hinhthucdg,
            'diem_pc'    => $diem_pc,
            'maHKNH'     => $maHKNH,
            'sv'         => $sv,
        ]);
    }
}

<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SVHocKyController extends Controller
{
    public function index()
    {
        $tenSV = Session::get('tenSV');
        $maSSV = Session::get('maSSV');

        // lấy danh sách học phần bao gồm tên môn và số tín chỉ
        $danhSachHocPhan = DB::table('nhom_lop')
            ->join(
                'sinh_vien_nhom_lop',
                'nhom_lop.maNhomLop',
                '=',
                'sinh_vien_nhom_lop.maNhomLop'
            )
            ->join(
                'hoc_phan',
                'nhom_lop.maHocPhan',
                '=',
                'hoc_phan.maHocPhan'
            )
            ->where('sinh_vien_nhom_lop.maSSV', $maSSV)
            ->select(
                'nhom_lop.maHKNH',
                'nhom_lop.maLop',
                'nhom_lop.maNhomLop',
                'hoc_phan.maHocPhan',
                'hoc_phan.tenHocPhan',
                'hoc_phan.tongSoTinChi'
            )
            ->orderBy('nhom_lop.maHKNH', 'desc')
            ->get();

        $maHocPhans = $danhSachHocPhan->pluck('maHocPhan')->unique()->toArray();
        $maNhomLops = $danhSachHocPhan->pluck('maNhomLop')->unique()->toArray();

        // Lấy tất cả hình thức đánh giá của các học phần này
        $hinhthucdg_all = DB::table('hocphan_hinhthuc_dg')
            ->whereIn('maHocPhan', $maHocPhans)
            ->where('isDelete', false)
            ->get(['maHocPhan', 'maLoaiDG', 'trongSo']);

        // Lấy tất cả điểm phiếu chấm của sinh viên này cho các nhóm lớp tương ứng
        $diem_pc_all = DB::table('phieu_cham')
            ->join('de_thi', 'phieu_cham.maDe', '=', 'de_thi.maDe')
            ->join('hocphan_hinhthuc_dg', 'de_thi.id_hocphan_hinhthucdg', '=', 'hocphan_hinhthuc_dg.id')
            ->where('phieu_cham.maSSV', $maSSV)
            ->where('phieu_cham.trangThai', 1)
            ->where('de_thi.isDelete', false)
            ->whereIn('de_thi.maNhomLop', $maNhomLops)
            ->select('hocphan_hinhthuc_dg.maHocPhan', 'de_thi.maNhomLop', 'hocphan_hinhthuc_dg.maLoaiDG', 'phieu_cham.diemSo')
            ->get();

        // Tách chuỗi maHKNH và tính điểm cho từng học phần
        $danhSachHocPhan->map(function ($item) use ($hinhthucdg_all, $diem_pc_all) {
            $htdg_course = $hinhthucdg_all->where('maHocPhan', $item->maHocPhan);
            $diem_course = $diem_pc_all->where('maNhomLop', $item->maNhomLop);

            $tongDiem = 0;
            $tongTS = 0;

            if ($htdg_course->count() > 0) {
                foreach ($htdg_course as $ht) {
                    $diem_loai = $diem_course->where('maLoaiDG', $ht->maLoaiDG);
                    if ($diem_loai->count() > 0) {
                        $diem_tb_loai = round($diem_loai->avg('diemSo'), 1);
                        $tongDiem += $diem_tb_loai * $ht->trongSo;
                    }
                    $tongTS += $ht->trongSo;
                }
                
                if ($tongTS > 0 && $diem_course->count() > 0) {
                    $item->diem = round($tongDiem / $tongTS, 1);
                } else {
                    $item->diem = null;
                }
            } else {
                $item->diem = null;
            }

            // Tách chuỗi maHKNH (ví dụ: "HK1-2022-2023") thành học kỳ và năm học
            $parts = explode('-', $item->maHKNH, 2);
            if (count($parts) == 2) {
                $item->hocKy = $parts[0]; // Ví dụ: HK1
                $item->namHoc = $parts[1]; // Ví dụ: 2022-2023
            } else {
                $item->hocKy = 'Khác';
                $item->namHoc = $item->maHKNH;
            }
            return $item;
        });

        // Nhóm dữ liệu theo Năm Học trước, sau đó bên trong mỗi năm học thì nhóm theo Học Kỳ
        $groupedByYear = $danhSachHocPhan->groupBy('namHoc')
            ->sortByDesc(function ($items, $key) {
                return $key; // Sắp xếp năm học giảm dần (ví dụ: 2022-2023 đứng trước 2021-2022)
            })
            ->map(function ($yearItems) {
                return $yearItems->groupBy('hocKy')->sortBy(function ($items, $key) {
                    return $key; // Sắp xếp học kỳ tăng dần (HK1, HK2, ...)
                });
            });

        // Chuẩn bị dữ liệu vẽ biểu đồ
        $chartLable = []; // lưu tên học kì theop thời gian tăng dần
        $chartData = []; // lưu điểm trung bình tương ứng

        $danhSachHocPhan->groupBy('namHoc')
        ->sortBy(function($item, $key){ return $key;})
        ->each(function($yearItems, $namHoc) use(&$chartLable, &$chartData){
            $yearItems->groupBy('hocKy')->sortBy(function($items, $key){
                return $key;
            })
            ->each(function($semesterCourses, $hocKy) use(&$namHoc, &$chartLable, &$chartData){
                $tongDiem = 0;
                $soHocPhan = 0;

                foreach($semesterCourses as $course){
                    if(isset($course->diem) && is_numeric($course->diem)){
                        $tongDiem += $course->diem;
                        $soHocPhan++;
                    }
                }
                // tính điểm trung bình học kỳ bằng trung bình điểm học phần thuộc học kỳ đó
                $diemTB = ($soHocPhan > 0) ? round($tongDiem / $soHocPhan, 2) : 0;
                if($soHocPhan > 0){
                    $chartLable[] = $hocKy.' - '.$namHoc;
                    $chartData[] = $diemTB;
                }
            });
        });

        //dữ liệu sắp xếp theo thời gian giảm dần
        $groupedByYear = $danhSachHocPhan->groupBy('namHoc')->sortByDesc(function($item, $key){
            return $key;
        })
        ->map(function($yearItems){
            return $yearItems->groupBy('hocKy')->sortByDesc(function($items, $key){
                return $key;
            })
            ->map(function($semesterCourses) {
                $tongDiem = 0;
                $soHocPhan = 0;
                
                foreach ($semesterCourses as $course) {
                    if (isset($course->diem) && is_numeric($course->diem)) {
                        $tongDiem += $course->diem;
                        $soHocPhan++;
                    }
                }

                return (object) [
                    'courses' => $semesterCourses,
                    'diemTB' => ($soHocPhan > 0) ? round($tongDiem / $soHocPhan, 2) : 'Chưa có dữ liệu'
                ];
            });
        });
        
        return view('sinhvien.hocphan.hocky', compact(
            'tenSV', 'groupedByYear', 'chartLable', 'chartData'
        ));
    }
}
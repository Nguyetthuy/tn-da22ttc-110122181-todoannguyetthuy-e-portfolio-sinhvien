<?php

namespace App\Http\Controllers\CoVan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\coVanLop;

use App\Models\hocPhan_HinhThucDG;
use App\Models\hocPhan_CDR_HP;
use App\Models\hocPhan;
use App\Models\thongke_clo_hocphan;

class CoVanThongKeController extends Controller
{
    public function danhsach_hocky($maLop)
{
    $maGV = Session::get('maGV');

    // 1. Xác thực quyền cố vấn học tập
    $checkCoVan = DB::table('co_van_lop')
        ->where('maGV', $maGV)
        ->where('maLop', $maLop)
        ->exists();

    if (!$checkCoVan) {
        return redirect()->back()->with('error', 'Bạn không có quyền quản lý lớp này!');
    }

    // 2. Lấy tất cả lịch sử giảng dạy của lớp hành chính này (Giữ nguyên cấu trúc select giống Bộ môn)
    $gd_data = DB::select("
        SELECT 
            giangday.id, 
            giangday.maHocPhan, 
            giangday.maHKNH, 
            giangday.maLop, 
            giangday.maGV, 
            hoc_phan.tenHocPhan, 
            giang_vien.hoGV, 
            giang_vien.tenGV, 
            lop_hanh_chinh.tenLop 
        FROM giangday
        JOIN hoc_phan ON giangday.maHocPhan = hoc_phan.maHocPhan
        JOIN giang_vien ON giangday.maGV = giang_vien.maGV
        JOIN lop_hanh_chinh ON giangday.maLop = lop_hanh_chinh.maLop
        WHERE giangday.maLop = ? 
          AND giangday.isDelete = 0
        ORDER BY giangday.maHKNH DESC, giangday.maHocPhan DESC
    ", [$maLop]);

    // 3. THUẬT TOÁN ĐẾM ĐA CẤP ĐỂ GỘP HÀNG (ROWSPAN)
    $countNamHoc = [];
    $countHocKy = [];

    foreach ($gd_data as $row) {
        $mang = explode('-', $row->maHKNH);
        $hk = $mang[0] ?? $row->maHKNH;
        $nam = (isset($mang[1]) && isset($mang[2])) ? $mang[1] . '-' . $mang[2] : 'Chưa rõ';

        // Đếm tổng số dòng của mỗi Năm học
        if (!isset($countNamHoc[$nam])) {
            $countNamHoc[$nam] = 0;
        }
        $countNamHoc[$nam]++;

        // Đếm tổng số dòng của mỗi Học kỳ thuộc Năm học đó
        if (!isset($countHocKy[$row->maHKNH])) {
            $countHocKy[$row->maHKNH] = 0;
        }
        $countHocKy[$row->maHKNH]++;
    }

    // 3. Lấy dữ liệu tổng hợp gộp hàng (Phòng hờ trường hợp một môn có nhiều giảng viên dạy)
    $tonghopgd = DB::table('giangday')
        ->selectRaw('maHKNH, maHocPhan, maLop, count(maHKNH) as slgvmon') 
        ->where('maLop', $maLop)
        ->where('isDelete', false)  
        ->groupBy('maHKNH', 'maHocPhan', 'maLop')
        ->get();

    // 5. Thống kê PLO lớp học
    $lopInfo = DB::table('lop_hanh_chinh')
        ->where('maLop', $maLop)
        ->where('isDelete', false)
        ->first();

    $students = DB::table('sinh_vien')
        ->where('maLop', $maLop)
        ->where('isDelete', false)
        ->select('maSSV')
        ->get();

    $student_ids = $students->pluck('maSSV')->toArray();
    $total_students = count($students);

    $plo_stats = [];
    if ($lopInfo) {
        $plos = DB::table('cdr_ctdt')
            ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
            ->where('khoa_tuyensinh_ct_daotao.maCT', $lopInfo->maCT)
            ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $lopInfo->maKhoaTuyenSinh)
            ->where('cdr_ctdt.isDelete', false)
            ->where('khoa_tuyensinh_ct_daotao.isDelete', false)
            ->select('cdr_ctdt.maCDR_CTDT', 'cdr_ctdt.maCDR_CTDT_VB', 'cdr_ctdt.tenCDR_CTDT')
            ->distinct()
            ->get()
            ->sortBy(function($item) {
                return preg_replace_callback('/\d+/', function($m) {
                    return sprintf('%04d', $m[0]);
                }, $item->maCDR_CTDT_VB);
            })
            ->values();

        foreach ($plos as $plo) {
            $plo_stats[$plo->maCDR_CTDT] = [
                'maCDR_CTDT_VB'  => $plo->maCDR_CTDT_VB,
                'tenCDR_CTDT'    => $plo->tenCDR_CTDT,
                'dat'            => 0,
                'chua_dat'       => $total_students,
                'ty_le_dat'      => 0.0,
                'ty_le_chua_dat' => 100.0,
            ];
        }

        if ($total_students > 0) {
            $plo_scores = DB::table('thongke_plo_sinhvien')
                ->join('cdr_ctdt', 'thongke_plo_sinhvien.maCDR_CTDT', '=', 'cdr_ctdt.maCDR_CTDT')
                ->join('khoa_tuyensinh_ct_daotao', 'cdr_ctdt.maCDR_CTDT', '=', 'khoa_tuyensinh_ct_daotao.maCDR_CTDT')
                ->whereIn('thongke_plo_sinhvien.maSSV', $student_ids)
                ->where('cdr_ctdt.isDelete', false)
                ->where('khoa_tuyensinh_ct_daotao.isDelete', false)
                ->where('khoa_tuyensinh_ct_daotao.maCT', $lopInfo->maCT)
                ->where('khoa_tuyensinh_ct_daotao.maKhoaTuyenSinh', $lopInfo->maKhoaTuyenSinh)
                ->select(
                    'thongke_plo_sinhvien.maSSV',
                    'cdr_ctdt.maCDR_CTDT',
                    DB::raw('100 - SUM(CASE WHEN thongke_plo_sinhvien.ty_le_dat < 40 THEN thongke_plo_sinhvien.ty_le_dong_gop ELSE 0 END) as ty_le_dat_tb')
                )
                ->groupBy('thongke_plo_sinhvien.maSSV', 'cdr_ctdt.maCDR_CTDT')
                ->get();

            foreach ($plo_scores as $score) {
                if (isset($plo_stats[$score->maCDR_CTDT])) {
                    if ($score->ty_le_dat_tb >= 70) {
                        $plo_stats[$score->maCDR_CTDT]['dat']++;
                        $plo_stats[$score->maCDR_CTDT]['chua_dat']--;
                    }
                }
            }

            foreach ($plo_stats as $key => $stat) {
                $plo_stats[$key]['ty_le_dat'] = round(($stat['dat'] / $total_students) * 100, 2);
                $plo_stats[$key]['ty_le_chua_dat'] = round(($stat['chua_dat'] / $total_students) * 100, 2);
            }
        }
    }

    return view('covan.thongkelop_hocky', [
        'giangday'   => $gd_data,
        'tonghopgd'  => $tonghopgd,
        'maLop'      => $maLop,
        'plo_stats'  => $plo_stats,
    ]);
}


    public function clo($maHocPhan, $maGV, $maLop, $maHKNH)
    {
        //lấy các lần đánh giá từ $maHocPhan
        $lan_danh_gia = hocPhan_HinhThucDG::where('maHocPhan', $maHocPhan)->get();
        
        $thongke = [];
        //lấy tất cả các chuẩn cdr_hp
        $cdr_hp = hocPhan_CDR_HP::where('maHocPhan', $maHocPhan)
            ->join('cdr_hp', function($x){
                $x->on('hocphan_cdr_hp.maCDR_HP', '=', 'cdr_hp.maCDR_HP')
                  ->where('cdr_hp.isDelete', false)
                  ->orderBy('maCDR_HP_VB');
            })->get();
        
        foreach($cdr_hp as $cdr){
            $so_lan_danh_gia = 0; //đếm xem $cdr được đánh giá mấy lần
            $dat_A = $dat_B = $dat_C = $dat_D = 0;
            $temp = [];
           
            foreach($lan_danh_gia as $ct){
                $tk = thongke_clo_hocphan::where('id_hocphan_hinhthuc_dg', $ct->id)
                    ->where('maCDR_HP', $cdr->maCDR_HP)
                    ->where('maCanBo', $maGV)
                    ->whereIn('maNhomLop', function($q) use ($maLop, $maHocPhan, $maHKNH) {
                        $q->select('maNhomLop')->from('nhom_lop')->where('maLop', $maLop)->where('maHocPhan', $maHocPhan)->where('maHKNH', $maHKNH);
                    })->get();
                if(count($tk) > 0 && $tk->sum('dat_A') !== null){
                    $so_lan_danh_gia++;
                }
            }
            
            if($so_lan_danh_gia > 0){
                array_push($temp, $cdr->maCDR_HP_VB);
                array_push($temp, $cdr->tenCDR_HP);
                
                foreach($lan_danh_gia as $ct){
                    $thongke_so_hocphan = thongke_clo_hocphan::where('id_hocphan_hinhthuc_dg', $ct->id)
                        ->where('maCDR_HP', $cdr->maCDR_HP)
                        ->where('maCanBo', $maGV)
                        ->whereIn('maNhomLop', function($q) use ($maLop, $maHocPhan, $maHKNH) {
                            $q->select('maNhomLop')->from('nhom_lop')->where('maLop', $maLop)->where('maHocPhan', $maHocPhan)->where('maHKNH', $maHKNH);
                        })
                        ->selectRaw('SUM(dat_A) as dat_A, SUM(dat_B) as dat_B, SUM(dat_C) as dat_C, SUM(dat_D) as dat_D, SUM(chua_dat) as chua_dat')
                        ->get();
                   
                    if(count($thongke_so_hocphan) == 1 && $thongke_so_hocphan[0]->dat_A !== null){
                        $tong = $thongke_so_hocphan[0]->dat_A + $thongke_so_hocphan[0]->dat_B + $thongke_so_hocphan[0]->dat_C + $thongke_so_hocphan[0]->dat_D + $thongke_so_hocphan[0]->chua_dat;
                        if($tong != 0){
                            // Áp dụng công thức tính theo trọng số (%) của lần đánh giá cho n lần
                            $dat_A += ($thongke_so_hocphan[0]->dat_A / $tong) * ($ct->trongSo / 100);
                            $dat_B += ($thongke_so_hocphan[0]->dat_B / $tong) * ($ct->trongSo / 100);
                            $dat_C += ($thongke_so_hocphan[0]->dat_C / $tong) * ($ct->trongSo / 100);
                            $dat_D += ($thongke_so_hocphan[0]->dat_D / $tong) * ($ct->trongSo / 100);
                        }
                    }
                }
                
                array_push($temp, number_format($dat_A * 100, 2));
                array_push($temp, number_format($dat_B * 100, 2));
                array_push($temp, number_format($dat_C * 100, 2));
                array_push($temp, number_format($dat_D * 100, 2));
                array_push($thongke, $temp); 
            }
        }
        
        $hocPhan = hocPhan::where('maHocPhan', $maHocPhan)->where('isDelete', false)->first();
        return view('covan.thongke_HP', ['bieuDo' => $thongke, 'hocPhan' => $hocPhan, 'maHKNH' => $maHKNH]);
    }

    public function plo($maHocPhan, $maGV, $maLop, $maHKNH)
    {
        $kq = DB::select("
            SELECT 
                thongke_elo_hocphan.maCDR_CTDT, 
                cdr_ctdt.maCDR_CTDT_VB, 
                cdr_ctdt.tenCDR_CTDT, 
                SUM(thongke_elo_hocphan.dat_A * hocphan_hinhthuc_dg.trongSo) as 'A', 
                SUM(thongke_elo_hocphan.dat_B * hocphan_hinhthuc_dg.trongSo) as 'B', 
                SUM(thongke_elo_hocphan.dat_C * hocphan_hinhthuc_dg.trongSo) as 'C', 
                SUM(thongke_elo_hocphan.dat_D * hocphan_hinhthuc_dg.trongSo) as 'D',
                SUM(thongke_elo_hocphan.chua_dat * hocphan_hinhthuc_dg.trongSo) as 'Chua_dat', 
                (SUM(thongke_elo_hocphan.dat_A * hocphan_hinhthuc_dg.trongSo) + 
                 SUM(thongke_elo_hocphan.dat_B * hocphan_hinhthuc_dg.trongSo) + 
                 SUM(thongke_elo_hocphan.dat_C * hocphan_hinhthuc_dg.trongSo) + 
                 SUM(thongke_elo_hocphan.dat_D * hocphan_hinhthuc_dg.trongSo) + 
                 SUM(thongke_elo_hocphan.chua_dat * hocphan_hinhthuc_dg.trongSo)) as 'Tong'
            FROM thongke_elo_hocphan
            JOIN cdr_ctdt ON thongke_elo_hocphan.maCDR_CTDT = cdr_ctdt.maCDR_CTDT 
            JOIN hocphan_hinhthuc_dg ON thongke_elo_hocphan.id_hocphan_hinhthuc_dg = hocphan_hinhthuc_dg.id
            WHERE hocphan_hinhthuc_dg.maHocPhan = ?
              AND thongke_elo_hocphan.maCanBo = ?
              AND thongke_elo_hocphan.maNhomLop IN (
                  SELECT maNhomLop FROM nhom_lop WHERE maLop = ? AND maHocPhan = ? AND maHKNH = ?
              )
            GROUP BY thongke_elo_hocphan.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT
            ORDER BY cdr_ctdt.maCDR_CTDT_VB
        ", [$maHocPhan, $maGV, $maLop, $maHocPhan, $maHKNH]);

        $hocPhan = hocPhan::where('maHocPhan', $maHocPhan)->where('isDelete', false)->first();
        return view('covan.thongke_CTDT', ['thong_ke_elo' => $kq, 'hocPhan' => $hocPhan, 'maHKNH' => $maHKNH]);
    }
    
}
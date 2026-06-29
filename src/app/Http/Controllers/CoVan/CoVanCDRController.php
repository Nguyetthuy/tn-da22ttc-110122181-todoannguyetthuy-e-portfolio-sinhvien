<?php

namespace App\Http\Controllers\CoVan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\hocPhan;

class CoVanCDRController extends Controller
{
    public function monhoc($maSSV, $maHocPhan, $maLop)
    {
        
        $nhomLop = DB::table('nhom_lop')
            ->join('sinh_vien_nhom_lop', 'nhom_lop.maNhomLop', '=', 'sinh_vien_nhom_lop.maNhomLop')
            ->where('nhom_lop.maHocPhan', $maHocPhan)
            ->where('nhom_lop.maLop', $maLop)
            ->where('sinh_vien_nhom_lop.maSSV', $maSSV)
            ->where('nhom_lop.isDelete', false)
            ->where('sinh_vien_nhom_lop.isDelete', false)
            ->first(['nhom_lop.maNhomLop']);

        $maNhomLop = $nhomLop ? $nhomLop->maNhomLop : $maLop;

        Session::put('maHocPhan', $maHocPhan);
        Session::put('maNhomLop', $maNhomLop);

        // Lấy maHKNH từ nhom_lop
        $hknh = DB::table('nhom_lop')
            ->where('maNhomLop', $maNhomLop)
            ->value('maHKNH');

        Session::put('maHKNH', $hknh);


        return $this->tinh_CDR_HP($maSSV);
    }

    private function tinh_CDR_HP($maSSV)
    {
        $mang2chieu = [[]];
        $sodong = 0;

        $maHocPhan = Session::get('maHocPhan');
        $maNhomLop = Session::get('maNhomLop');

        $dshinh_thucDG = DB::select(
            "SELECT * FROM hocphan_hinhthuc_dg WHERE maHocPhan = '$maHocPhan' AND isDelete = false ORDER BY id"
        );

        $kqCheck = DB::select(
            "SELECT phieu_cham.maPhieuCham FROM phieu_cham, de_thi, hocphan_hinhthuc_dg
            WHERE phieu_cham.maDe IN (
                SELECT de_thi.maDe FROM de_thi
                WHERE de_thi.id_hocphan_hinhthucdg IN (
                    SELECT id FROM hocphan_hinhthuc_dg WHERE maHocPhan = '$maHocPhan'
                ) AND de_thi.isDelete = false AND de_thi.maNhomLop = $maNhomLop
            )
            AND phieu_cham.maSSV = '$maSSV'
            AND phieu_cham.maDe = de_thi.maDe
            AND de_thi.id_hocphan_hinhthucdg = hocphan_hinhthuc_dg.id
            AND phieu_cham.trangThai = 1"
        );

        $Sinh_vien = DB::select("SELECT * FROM sinh_vien WHERE maSSV = '$maSSV'");
        $hocPhanModel = hocPhan::where('maHocPhan', $maHocPhan)->where('isDelete', false)
            ->first(['maHocPhan', 'maHocPhan_VB', 'tenHocPhan']);

        if (count($kqCheck) == 0) {
            return view('sinhvien.hocphan.kq_cdr', [
                "hocPhan"    => $hocPhanModel,
                "Sinh_vien"  => $Sinh_vien,
                "mang2chieu" => [],
                "maHKNH"     => Session::get('maHKNH'),
                "message"    => "Không có dữ liệu CĐR cho học phần này!"
            ]);
        }

        foreach ($dshinh_thucDG as $dshtdg) {
            $kqdsphieucham = DB::select(
                "SELECT phieu_cham.maPhieuCham, hocphan_hinhthuc_dg.trongSo, hocphan_hinhthuc_dg.maHTDG, hocphan_hinhthuc_dg.maLoaiDG
                FROM phieu_cham, de_thi, hocphan_hinhthuc_dg
                WHERE phieu_cham.maDe IN (
                    SELECT de_thi.maDe FROM de_thi
                    WHERE de_thi.id_hocphan_hinhthucdg IN (
                        SELECT id FROM hocphan_hinhthuc_dg WHERE maHocPhan = '$maHocPhan'
                    ) AND de_thi.isDelete = false AND de_thi.maNhomLop = $maNhomLop
                )
                AND phieu_cham.maSSV = '$maSSV'
                AND hocphan_hinhthuc_dg.id = {$dshtdg->id}
                AND phieu_cham.maDe = de_thi.maDe
                AND de_thi.id_hocphan_hinhthucdg = hocphan_hinhthuc_dg.id"
            );

            if (count($kqdsphieucham) == 0) continue;

            $maPhieuCham = $kqdsphieucham[0]->maPhieuCham;
            $trongSo     = $kqdsphieucham[0]->trongSo;
            $maHTDG      = $dshtdg->maHTDG;
            $maLoaiDG    = $dshtdg->maLoaiDG;

            if ($maHTDG == 'T1') {
                $kq = DB::select(
                    "SELECT cau_hoi.maCDR_HP, cdr_hp.maCDR_HP_VB, cdr_hp.tenCDR_HP,
                     SUM(phuong_an_tu_luan.diemPA) as DiemPA, SUM(danhgia_tuluan.diemDG) as DiemDG
                    FROM cau_hoi, de_thi_cau_hoi, danhgia_tuluan, phuong_an_tu_luan, cdr_hp
                    WHERE cau_hoi.maCauHoi = de_thi_cau_hoi.maCauHoi
                    AND cau_hoi.maCDR_HP = cdr_hp.maCDR_HP
                    AND phuong_an_tu_luan.maDeThi_cauHoi = de_thi_cau_hoi.id
                    AND danhgia_tuluan.maPATuLuan = phuong_an_tu_luan.maPATuLuan
                    AND danhgia_tuluan.maPhieuCham = $maPhieuCham
                    GROUP BY cau_hoi.maCDR_HP ORDER BY cau_hoi.maCDR_HP"
                );
                foreach ($kq as $r) {
                    if ($r->DiemPA == 0) continue;
                    $mang2chieu[$sodong][0] = $r->maCDR_HP;
                    $mang2chieu[$sodong][1] = $r->maCDR_HP_VB;
                    $mang2chieu[$sodong][2] = $r->tenCDR_HP;
                    $mang2chieu[$sodong][3] = $r->DiemDG / $r->DiemPA * 100;
                    $mang2chieu[$sodong][4] = $trongSo;
                    $mang2chieu[$sodong][5] = $maLoaiDG;
                    $sodong++;
                }

            } elseif ($maHTDG == 'T2') {
                $sc = DB::select(
                    "SELECT COUNT(cau_hoi.maCauHoi) as TongCauHoi
                    FROM cau_hoi, de_thi_cau_hoi, danhgia_tracnghiem, phuong_an_trac_nghiem
                    WHERE cau_hoi.maCauHoi = de_thi_cau_hoi.maCauHoi
                    AND cau_hoi.maCauHoi = phuong_an_trac_nghiem.maCauHoi
                    AND cau_hoi.isDelete = false AND danhgia_tracnghiem.isDelete = false
                    AND de_thi_cau_hoi.isDelete = false AND phuong_an_trac_nghiem.isDelete = false
                    AND danhgia_tracnghiem.maPATracNghiem = phuong_an_trac_nghiem.maPATracnghiem
                    AND danhgia_tracnghiem.maPhieuCham = $maPhieuCham"
                );
                $diem1cau = (count($sc) > 0 && $sc[0]->TongCauHoi > 0) ? 10 / $sc[0]->TongCauHoi : 0;
                $kq = DB::select(
                    "SELECT cau_hoi.maCDR_HP, cdr_hp.maCDR_HP_VB, cdr_hp.tenCDR_HP,
                     COUNT(cau_hoi.maCDR_HP) as SoCauHoi, SUM(danhgia_tracnghiem.diem) as diem
                    FROM cau_hoi, de_thi_cau_hoi, danhgia_tracnghiem, phuong_an_trac_nghiem, cdr_hp
                    WHERE cau_hoi.maCauHoi = de_thi_cau_hoi.maCauHoi
                    AND cdr_hp.maCDR_HP = cau_hoi.maCDR_HP
                    AND cau_hoi.maCauHoi = phuong_an_trac_nghiem.maCauHoi
                    AND danhgia_tracnghiem.maPATracNghiem = phuong_an_trac_nghiem.maPATracnghiem
                    AND danhgia_tracnghiem.maPhieuCham = $maPhieuCham
                    GROUP BY cau_hoi.maCDR_HP ORDER BY cau_hoi.maCDR_HP"
                );
                foreach ($kq as $r) {
                    $tam = $r->SoCauHoi * $diem1cau;
                    if ($tam == 0) continue;
                    $mang2chieu[$sodong][0] = $r->maCDR_HP;
                    $mang2chieu[$sodong][1] = $r->maCDR_HP_VB;
                    $mang2chieu[$sodong][2] = $r->tenCDR_HP;
                    $mang2chieu[$sodong][3] = $r->diem / $tam * 100;
                    $mang2chieu[$sodong][4] = $trongSo;
                    $sodong++;
                }

            } elseif ($maHTDG == 'T3') {
                if ($maLoaiDG == 4 && count($kqdsphieucham) >= 2) {
                    $pc1 = $kqdsphieucham[0]->maPhieuCham;
                    $pc2 = $kqdsphieucham[1]->maPhieuCham;
                    $sqlTH = "SELECT cau_hoi.maCDR_HP, cdr_hp.maCDR_HP_VB, cdr_hp.tenCDR_HP,
                        SUM(phuong_an_tu_luan.diemPA) as DiemPA, SUM(danhgia_tuluan.diemDG) as DiemDG
                        FROM cau_hoi, de_thi_cau_hoi, danhgia_tuluan, phuong_an_tu_luan, cdr_hp
                        WHERE cau_hoi.maCauHoi = de_thi_cau_hoi.maCauHoi
                        AND cau_hoi.maCDR_HP = cdr_hp.maCDR_HP
                        AND phuong_an_tu_luan.maDeThi_cauHoi = de_thi_cau_hoi.id
                        AND danhgia_tuluan.maPATuLuan = phuong_an_tu_luan.maPATuLuan
                        AND danhgia_tuluan.maPhieuCham = %d
                        GROUP BY cau_hoi.maCDR_HP ORDER BY cau_hoi.maCDR_HP";
                    $kq1 = DB::select(sprintf($sqlTH, $pc1));
                    $kq2 = DB::select(sprintf($sqlTH, $pc2));
                    foreach ($kq1 as $i => $r) {
                        if ($r->DiemPA == 0) continue;
                        $d1 = $r->DiemDG / $r->DiemPA * 100;
                        $d2 = isset($kq2[$i]) && $kq2[$i]->DiemPA > 0 ? $kq2[$i]->DiemDG / $kq2[$i]->DiemPA * 100 : 0;
                        $mang2chieu[$sodong][0] = $r->maCDR_HP;
                        $mang2chieu[$sodong][1] = $r->maCDR_HP_VB;
                        $mang2chieu[$sodong][2] = $r->tenCDR_HP;
                        $mang2chieu[$sodong][3] = ($d1 + $d2) / 2;
                        $mang2chieu[$sodong][4] = $trongSo;
                        $mang2chieu[$sodong][5] = $maLoaiDG;
                        $sodong++;
                    }
                } else {
                    $kq = DB::select(
                        "SELECT cau_hoi.maCDR_HP, cdr_hp.maCDR_HP_VB, cdr_hp.tenCDR_HP,
                         SUM(phuong_an_tu_luan.diemPA) as DiemPA, SUM(danhgia_tuluan.diemDG) as DiemDG
                        FROM cau_hoi, de_thi_cau_hoi, danhgia_tuluan, phuong_an_tu_luan, cdr_hp
                        WHERE cau_hoi.maCauHoi = de_thi_cau_hoi.maCauHoi
                        AND cau_hoi.maCDR_HP = cdr_hp.maCDR_HP
                        AND phuong_an_tu_luan.maDeThi_cauHoi = de_thi_cau_hoi.id
                        AND danhgia_tuluan.maPATuLuan = phuong_an_tu_luan.maPATuLuan
                        AND danhgia_tuluan.maPhieuCham = $maPhieuCham
                        GROUP BY cau_hoi.maCDR_HP ORDER BY cau_hoi.maCDR_HP"
                    );
                    foreach ($kq as $r) {
                        if ($r->DiemPA == 0) continue;
                        $mang2chieu[$sodong][0] = $r->maCDR_HP;
                        $mang2chieu[$sodong][1] = $r->maCDR_HP_VB;
                        $mang2chieu[$sodong][2] = $r->tenCDR_HP;
                        $mang2chieu[$sodong][3] = $r->DiemDG / $r->DiemPA * 100;
                        $mang2chieu[$sodong][4] = $trongSo;
                        $mang2chieu[$sodong][5] = $maLoaiDG;
                        $sodong++;
                    }
                }

            } elseif ($maHTDG == 'T8' && count($kqdsphieucham) >= 3) {
                $sqlDoan = "SELECT tieu_chi_doan.maCDR_HP, cdr_hp.maCDR_HP_VB, cdr_hp.tenCDR_HP,
                    SUM(tieu_chi_doan.diemTieuChiDoan) as Tongdiem, SUM(danh_gia_doan.diemDG) as diemdg
                    FROM tieu_chi_doan, cdr_hp, danh_gia_doan
                    WHERE tieu_chi_doan.maCDR_HP = cdr_hp.maCDR_HP
                    AND tieu_chi_doan.maTieuChiDoan = danh_gia_doan.maTieuChiDoan
                    AND danh_gia_doan.maPhieuCham = %d GROUP BY tieu_chi_doan.maCDR_HP";
                $kq1 = DB::select(sprintf($sqlDoan, $kqdsphieucham[0]->maPhieuCham));
                $kq2 = DB::select(sprintf($sqlDoan, $kqdsphieucham[1]->maPhieuCham));
                $kq3 = DB::select(sprintf($sqlDoan, $kqdsphieucham[2]->maPhieuCham));
                foreach ($kq1 as $i => $r) {
                    if ($r->Tongdiem == 0) continue;
                    $d1 = $r->diemdg / $r->Tongdiem;
                    $d2 = isset($kq2[$i]) ? $kq2[$i]->diemdg / $kq2[$i]->Tongdiem : 0;
                    $d3 = isset($kq3[$i]) ? $kq3[$i]->diemdg / $kq3[$i]->Tongdiem : 0;
                    $mang2chieu[$sodong][0] = $r->maCDR_HP;
                    $mang2chieu[$sodong][1] = $r->maCDR_HP_VB;
                    $mang2chieu[$sodong][2] = $r->tenCDR_HP;
                    $mang2chieu[$sodong][3] = ($d1 + $d2 + $d3) / 3 * 100;
                    $mang2chieu[$sodong][4] = $trongSo;
                    $mang2chieu[$sodong][5] = $maLoaiDG;
                    $sodong++;
                }

            } else {
                // T4,T5,T6,T7,T9,T10...T19 - Rubric
                $sqlRubric = "SELECT tieu_chi_rubric.maCDR_HP, cdr_hp.maCDR_HP_VB, cdr_hp.tenCDR_HP,
                    SUM(tieu_chi_rubric.trongSo) as Trongso, 
                    SUM(danh_gia_rubric.diem * tieu_chi_rubric.trongSo) as weighted_diem
                    FROM cdr_hp, danh_gia_rubric, phieu_cham, tieu_chi_rubric, muc_do_cl
                    WHERE danh_gia_rubric.maPhieuCham = phieu_cham.maPhieuCham
                    AND cdr_hp.maCDR_HP = tieu_chi_rubric.maCDR_HP
                    AND danh_gia_rubric.maTieuChiRubric = tieu_chi_rubric.maTieuChiRubric
                    AND danh_gia_rubric.maMucDoCL = muc_do_cl.maMucDoCL
                    AND phieu_cham.maPhieuCham = %d 
                    GROUP BY tieu_chi_rubric.maCDR_HP, cdr_hp.maCDR_HP_VB, cdr_hp.tenCDR_HP";

                if ($maLoaiDG == 4 && count($kqdsphieucham) >= 2) {
                    $kqR1 = DB::select(sprintf($sqlRubric, $kqdsphieucham[0]->maPhieuCham));
                    $kqR2 = DB::select(sprintf($sqlRubric, $kqdsphieucham[1]->maPhieuCham));
                    
                    $arrR2 = [];
                    foreach ($kqR2 as $item) {
                        $arrR2[$item->maCDR_HP] = $item;
                    }
                    
                    foreach ($kqR1 as $r) {
                        if ($r->Trongso == 0) continue;
                        $d1 = $r->weighted_diem / $r->Trongso;
                        $d2 = (isset($arrR2[$r->maCDR_HP]) && $arrR2[$r->maCDR_HP]->Trongso > 0) ? $arrR2[$r->maCDR_HP]->weighted_diem / $arrR2[$r->maCDR_HP]->Trongso : 0;
                        $mang2chieu[$sodong][0] = $r->maCDR_HP;
                        $mang2chieu[$sodong][1] = $r->maCDR_HP_VB;
                        $mang2chieu[$sodong][2] = $r->tenCDR_HP;
                        $mang2chieu[$sodong][3] = ($d1 + $d2) / 2;
                        $mang2chieu[$sodong][4] = $trongSo;
                        $mang2chieu[$sodong][5] = $maLoaiDG;
                        $sodong++;
                    }
                } else {
                    $kq = DB::select(sprintf($sqlRubric, $maPhieuCham));
                    foreach ($kq as $r) {
                        if ($r->Trongso == 0) continue;
                        $mang2chieu[$sodong][0] = $r->maCDR_HP;
                        $mang2chieu[$sodong][1] = $r->maCDR_HP_VB;
                        $mang2chieu[$sodong][2] = $r->tenCDR_HP;
                        $mang2chieu[$sodong][3] = $r->weighted_diem / $r->Trongso;
                        $mang2chieu[$sodong][4] = $trongSo;
                        $mang2chieu[$sodong][5] = $maLoaiDG;
                        $sodong++;
                    }
                }
            }
        }

        if (count($mang2chieu) <= 1 && empty($mang2chieu[0])) {
            return view('covan.cdr', [
                "hocPhan"    => $hocPhanModel,
                "Sinh_vien"  => $Sinh_vien,
                "mang2chieu" => [],
                 "maHKNH"     => Session::get('maHKNH'),
                "message"    => "Chưa có dữ liệu đánh giá CĐR cho học phần này!"
            ]);
        }

        return view('covan.cdr', [
            "hocPhan"    => $hocPhanModel,
            "Sinh_vien"  => $Sinh_vien,
            "mang2chieu" => $mang2chieu,
            "maHKNH"     => Session::get('maHKNH'),
        ]);
    }

    public function thong_ke_chi_tiet_KQHT_SinhVien_PI($maSSV)
    {

    $mang2chieu= array (array());
    $sodong=0; 
 
    $maHocPhan = Session::get('maHocPhan');     
  //  return $maHocPhan;  
    $maNhomLop=  Session::get('maNhomLop');
    $sqlhtdg="SELECT * FROM `hocphan_hinhthuc_dg` WHERE hocphan_hinhthuc_dg.maHocPhan='".$maHocPhan."' and hocphan_hinhthuc_dg.isDelete=false ORDER by hocphan_hinhthuc_dg.id";
    $dshinh_thucDG= db::select($sqlhtdg); 
      

$sql= "SELECT phieu_cham.maPhieuCham, de_thi.maDe, hocphan_hinhthuc_dg.id, hocphan_hinhthuc_dg.trongSo, hocphan_hinhthuc_dg.maHTDG from phieu_cham, de_thi, hocphan_hinhthuc_dg WHERE 
phieu_cham.maDe in (
select de_thi.maDe from de_thi WHERE de_thi.id_hocphan_hinhthucdg in (
SELECT hocphan_hinhthuc_dg.id FROM `hocphan_hinhthuc_dg` WHERE  hocphan_hinhthuc_dg.maHocPhan='".$maHocPhan."') and de_thi.isDelete=false  and de_thi.maNhomLop= ".$maNhomLop." ORDER by de_thi.maDe)
 and phieu_cham.maSSV='".$maSSV."' and phieu_cham.maDe= de_thi.maDe and de_thi.id_hocphan_hinhthucdg= hocphan_hinhthuc_dg.id and `phieu_cham`.`trangThai`=1";
 
 $kqdsphieucham= db::select($sql);
 //return $kqdsphieucham;

//return $sql;
        if (count($kqdsphieucham) == 0) {
            $Sinh_vien_db = DB::select("SELECT * FROM sinh_vien WHERE maSSV = '$maSSV'");
            $hocPhan_db = hocPhan::where('maHocPhan', $maHocPhan)->where('isDelete', false)
                ->orderbyDesc('maHocPhan')->first(['maHocPhan', 'maHocPhan_VB', 'tenHocPhan']);
            return view('covan.cdr_ctdt', [
                "hocPhan"    => $hocPhan_db,
                "Sinh_vien"  => $Sinh_vien_db,
                "mang2chieu" => [],
                "maHKNH"     => Session::get('maHKNH'),
                "message"    => "Chưa có dữ liệu đánh giá CĐR CTĐT cho học phần này!"
            ]);
        }

 foreach($dshinh_thucDG as $dshtdg)
 { 
    
    $sql= "SELECT phieu_cham.maPhieuCham, de_thi.maDe, hocphan_hinhthuc_dg.id, hocphan_hinhthuc_dg.trongSo, hocphan_hinhthuc_dg.maHTDG from phieu_cham, de_thi, hocphan_hinhthuc_dg WHERE 
    phieu_cham.maDe in (select de_thi.maDe from de_thi WHERE de_thi.id_hocphan_hinhthucdg in (
    SELECT hocphan_hinhthuc_dg.id FROM `hocphan_hinhthuc_dg` WHERE  hocphan_hinhthuc_dg.maHocPhan='".$maHocPhan."') and de_thi.isDelete=false  and de_thi.maNhomLop= ".$maNhomLop." ORDER by de_thi.maDe)
    and phieu_cham.maSSV='".$maSSV."' and hocphan_hinhthuc_dg.id=".$dshtdg->id." and phieu_cham.maDe= de_thi.maDe and de_thi.id_hocphan_hinhthucdg= hocphan_hinhthuc_dg.id";
 
    $kqdsphieucham= db::select($sql);

    //return $kqdsphieucham;

    if(count($kqdsphieucham)>0)
    {
            if($dshtdg->maHTDG=="T1")//tự luận
            {
                $maPhieuCham=$kqdsphieucham[0]->maPhieuCham;
                $trongSo=$kqdsphieucham[0]->trongSo;
           
                $sql="SELECT cau_hoi.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT,  sum(phuong_an_tu_luan.diemPA) as 'DiemPA', sum(danhgia_tuluan.diemDG) as 'DiemDG' 
                FROM cau_hoi, de_thi_cau_hoi, danhgia_tuluan, phuong_an_tu_luan, cdr_ctdt
                WHERE cau_hoi.maCauHoi=de_thi_cau_hoi.maCauHoi
                              and cau_hoi.maCDR_CTDT= cdr_ctdt.maCDR_CTDT
                              and phuong_an_tu_luan.maDeThi_cauHoi= de_thi_cau_hoi.id and danhgia_tuluan.maPATuLuan= phuong_an_tu_luan.maPATuLuan
                              and danhgia_tuluan.maPhieuCham=".$maPhieuCham." 
                              GROUP by cau_hoi.maCDR_CTDT ORDER BY cdr_ctdt.maCDR_CTDT_VB";
               
                $ketquathongke= db::select($sql);

                foreach($ketquathongke as $kq)
                {
                    $mang2chieu[$sodong][0]= $kq->maCDR_CTDT;
                    $mang2chieu[$sodong][1]= $kq->maCDR_CTDT_VB;
                    $mang2chieu[$sodong][2]= $kq->tenCDR_CTDT;

                    $kq_diem= $kq->DiemDG/$kq->DiemPA *100 ;
                    $mang2chieu[$sodong][3]=$kq_diem;
                    $mang2chieu[$sodong][4]=$trongSo;
                    $mang2chieu[$sodong][5]=$dshtdg->maLoaiDG;
                    $sodong++;

                }         
            }    
            if($dshtdg->maHTDG=="T2")//trắc nghiệm
            {

                $maPhieuCham=$kqdsphieucham[0]->maPhieuCham;
                $trongSo=$kqdsphieucham[0]->trongSo;
            

                $sql1="SELECT  COUNT(cau_hoi.maCauHoi) as'TongCauHoi' 
                FROM cau_hoi, de_thi_cau_hoi, danhgia_tracnghiem, phuong_an_trac_nghiem
                WHERE cau_hoi.maCauHoi=de_thi_cau_hoi.maCauHoi
                and cau_hoi.maCauHoi= phuong_an_trac_nghiem.maCauHoi
                and cau_hoi.isDelete=false 
                and danhgia_tracnghiem.isDelete=false
                and de_thi_cau_hoi.isDelete=false 
                and phuong_an_trac_nghiem.isDelete=false
                and danhgia_tracnghiem.maPATracNghiem = phuong_an_trac_nghiem.maPATracnghiem 
                and danhgia_tracnghiem.maPhieuCham=".$maPhieuCham;

                $socauhoi= db::select($sql1);     
         
                $diem_1_cau= 10/$socauhoi[0]->TongCauHoi;       
     
                $sql="SELECT  cau_hoi.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, COUNT(cau_hoi.maCDR_CTDT) as 'SoCauHoi' , sum(danhgia_tracnghiem.diem) as 'diem' 
                FROM cau_hoi, de_thi_cau_hoi, danhgia_tracnghiem, phuong_an_trac_nghiem, cdr_ctdt
                WHERE cau_hoi.maCauHoi=de_thi_cau_hoi.maCauHoi 
                and cdr_ctdt.maCDR_CTDT=cau_hoi.maCDR_CTDT
                and cau_hoi.maCauHoi= phuong_an_trac_nghiem.maCauHoi
                and danhgia_tracnghiem.maPATracNghiem = phuong_an_trac_nghiem.maPATracnghiem 
                and danhgia_tracnghiem.maPhieuCham=".$maPhieuCham." GROUP by cau_hoi.maCDR_CTDT ORDER BY cdr_ctdt.maCDR_CTDT_VB";
                     
                
                     $ketquathongke= db::select($sql);

                foreach($ketquathongke as $kq)
                {
                    $mang2chieu[$sodong][0]= $kq->maCDR_CTDT;
                    $mang2chieu[$sodong][1]= $kq->maCDR_CTDT_VB;
                    $mang2chieu[$sodong][2]= $kq->tenCDR_CTDT;

                    $tam= $kq->SoCauHoi* $diem_1_cau;

                    $kq_diem= $kq->diem/ $tam *100;
                
                    $mang2chieu[$sodong][3]=$kq_diem;
                    $mang2chieu[$sodong][4]=$trongSo;
                    $mang2chieu[$sodong][5]=$dshtdg->maLoaiDG;
                    $sodong++;
                }
              }
            if($dshtdg->maHTDG=="T3")//thực hành
            {
                if($dshtdg->maLoaiDG==4)//thực hành kết thúc môn
                {
                    $maPhieuCham1=$kqdsphieucham[0]->maPhieuCham;
                    $maPhieuCham2=$kqdsphieucham[1]->maPhieuCham;
                    $trongSo=$kqdsphieucham[0]->trongSo;
               
                    $sql1="SELECT cau_hoi.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, sum(phuong_an_tu_luan.diemPA) as 'DiemPA', sum(danhgia_tuluan.diemDG) as 'DiemDG' 
                    FROM cau_hoi, de_thi_cau_hoi, danhgia_tuluan, phuong_an_tu_luan, cdr_ctdt
                    WHERE cau_hoi.maCauHoi=de_thi_cau_hoi.maCauHoi
                    and cau_hoi.maCDR_CTDT= cdr_ctdt.maCDR_CTDT
                    and phuong_an_tu_luan.maDeThi_cauHoi= de_thi_cau_hoi.id and danhgia_tuluan.maPATuLuan= phuong_an_tu_luan.maPATuLuan
                    and danhgia_tuluan.maPhieuCham=".$maPhieuCham1." GROUP by cau_hoi.maCDR_CTDT ORDER BY cdr_ctdt.maCDR_CTDT_VB";
             
                   
                   $sql2="SELECT cau_hoi.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, sum(phuong_an_tu_luan.diemPA) as 'DiemPA', sum(danhgia_tuluan.diemDG) as 'DiemDG' 
                   FROM cau_hoi, de_thi_cau_hoi, danhgia_tuluan, phuong_an_tu_luan, cdr_ctdt
                   WHERE cau_hoi.maCauHoi=de_thi_cau_hoi.maCauHoi
                   and cau_hoi.maCDR_CTDT= cdr_ctdt.maCDR_CTDT
                   and phuong_an_tu_luan.maDeThi_cauHoi= de_thi_cau_hoi.id and danhgia_tuluan.maPATuLuan= phuong_an_tu_luan.maPATuLuan
                   and danhgia_tuluan.maPhieuCham=".$maPhieuCham2." GROUP by cau_hoi.maCDR_CTDT ORDER BY cdr_ctdt.maCDR_CTDT_VB";
                
                    $ketquathongke1= db::select($sql1);       
                    $ketquathongke2= db::select($sql2);
                    $chay=0;

                    foreach($ketquathongke1 as $kq)
                    {
                        $mang2chieu[$sodong][0]= $kq->maCDR_CTDT;
                        $mang2chieu[$sodong][1]= $kq->maCDR_CTDT_VB;
                        $mang2chieu[$sodong][2]= $kq->tenCDR_CTDT;
    
                        $kq_diem1= $kq->DiemDG/$kq->DiemPA *100 ;
                        $kq_diem2= $ketquathongke2[$chay]->DiemDG/$ketquathongke2[$chay]->DiemPA *100 ;

                        $kq_diem= ($kq_diem1+ $kq_diem2)/2;    

                        $mang2chieu[$sodong][3]=$kq_diem;
                        $mang2chieu[$sodong][4]=$trongSo;
                        $mang2chieu[$sodong][5]=$dshtdg->maLoaiDG;
                        $sodong++;   
                        $chay++; 
                    }
                }
                else//thực hành quá trình
                {

                    $maPhieuCham=$kqdsphieucham[0]->maPhieuCham;
                    $trongSo=$kqdsphieucham[0]->trongSo;    
                
                    $sql="SELECT cau_hoi.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, sum(phuong_an_tu_luan.diemPA) as 'DiemPA', sum(danhgia_tuluan.diemDG) as 'DiemDG' 
                    FROM cau_hoi, de_thi_cau_hoi, danhgia_tuluan, phuong_an_tu_luan, cdr_ctdt
                    WHERE cau_hoi.maCauHoi=de_thi_cau_hoi.maCauHoi
                    and cau_hoi.maCDR_CTDT= cdr_ctdt.maCDR_CTDT
                    and phuong_an_tu_luan.maDeThi_cauHoi= de_thi_cau_hoi.id and danhgia_tuluan.maPATuLuan= phuong_an_tu_luan.maPATuLuan
                    and danhgia_tuluan.maPhieuCham=".$maPhieuCham." GROUP by cau_hoi.maCDR_CTDT ORDER BY cdr_ctdt.maCDR_CTDT_VB";
                   
                    $ketquathongke= db::select($sql);
    
                    foreach($ketquathongke as $kq)
                    {
                        $mang2chieu[$sodong][0]= $kq->maCDR_CTDT;
                        $mang2chieu[$sodong][1]= $kq->maCDR_CTDT_VB;
                        $mang2chieu[$sodong][2]= $kq->tenCDR_CTDT;
    
                        $kq_diem= $kq->DiemDG/$kq->DiemPA *100 ;


                        $mang2chieu[$sodong][3]=$kq_diem;
                        $mang2chieu[$sodong][4]=$trongSo;
                        $sodong++;    
                    }

                }
            }
            if($dshtdg->maHTDG=="T8")//Đồ án môn học
            {

                $maPhieuCham1=$kqdsphieucham[0]->maPhieuCham;
                $maPhieuCham2=$kqdsphieucham[1]->maPhieuCham;
                $maPhieuCham3=$kqdsphieucham[2]->maPhieuCham;
                $trongSo=$kqdsphieucham[0]->trongSo;
          
                $sql1="SELECT tieu_chi_doan.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT,
                 SUM(tieu_chi_doan.diemTieuChiDoan) as 'Tongdiem', SUM(danh_gia_doan.diemDG) as 'diemdg' 
                FROM tieu_chi_doan, cdr_ctdt, danh_gia_doan 
                WHERE tieu_chi_doan.maCDR_CTDT= cdr_ctdt.maCDR_CTDT 
                and tieu_chi_doan.maTieuChiDoan= danh_gia_doan.maTieuChiDoan 
                and danh_gia_doan.maPhieuCham= ".$maPhieuCham1." 
                GROUP by tieu_chi_doan.maCDR_CTDT order by cdr_ctdt.maCDR_CTDT_VB";


                $sql2="SELECT tieu_chi_doan.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, 
                SUM(tieu_chi_doan.diemTieuChiDoan) as 'Tongdiem', SUM(danh_gia_doan.diemDG) as 'diemdg' 
                FROM tieu_chi_doan, cdr_ctdt, danh_gia_doan 
                WHERE tieu_chi_doan.maCDR_CTDT= cdr_ctdt.maCDR_CTDT 
                and tieu_chi_doan.maTieuChiDoan= danh_gia_doan.maTieuChiDoan 
                and danh_gia_doan.maPhieuCham= ".$maPhieuCham2." 
                GROUP by tieu_chi_doan.maCDR_CTDT order by cdr_ctdt.maCDR_CTDT_VB";
 
                $sql3="SELECT tieu_chi_doan.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, 
                SUM(tieu_chi_doan.diemTieuChiDoan) as 'Tongdiem', SUM(danh_gia_doan.diemDG) as 'diemdg' 
                FROM tieu_chi_doan, cdr_ctdt, danh_gia_doan 
                WHERE tieu_chi_doan.maCDR_CTDT= cdr_ctdt.maCDR_CTDT 
                and tieu_chi_doan.maTieuChiDoan= danh_gia_doan.maTieuChiDoan 
                and danh_gia_doan.maPhieuCham= ".$maPhieuCham3."
                GROUP by tieu_chi_doan.maCDR_CTDT order by cdr_ctdt.maCDR_CTDT_VB";

                $ketquathongke1= db::select($sql1);
                $ketquathongke2= db::select($sql2);
                $ketquathongke3= db::select($sql3);

                $chay=0;

                foreach($ketquathongke1 as $kq)
                {

                    $mang2chieu[$sodong][0]= $kq->maCDR_CTDT;
                    $mang2chieu[$sodong][1]= $kq->maCDR_CTDT_VB;
                    $mang2chieu[$sodong][2]= $kq->tenCDR_CTDT;  
                    
                    $kq1= $kq->diemdg/$kq->Tongdiem;
                    $kq2=$ketquathongke2[$chay]->diemdg/$ketquathongke2[$chay]->Tongdiem;     
                    $kq3=$ketquathongke3[$chay]->diemdg/$ketquathongke3[$chay]->Tongdiem;                              

                    $kq_diem=($kq1+$kq2+$kq3)/3 * 100;                              

                    $mang2chieu[$sodong][3]=$kq_diem;
                    $mang2chieu[$sodong][4]=$trongSo;
                        $mang2chieu[$sodong][5]=$dshtdg->maLoaiDG;
                    $sodong++;   
                    $chay++; 

                }              

             }
            if ($dshtdg->maHTDG=='T4' || $dshtdg->maHTDG=='T5' ||
            $dshtdg->maHTDG=='T6' || $dshtdg->maHTDG=='T7' || $dshtdg->maHTDG=='T9' || $dshtdg->maHTDG=='T10'
            ||$dshtdg->maHTDG=='T11' || $dshtdg->maHTDG=='T12' || $dshtdg->maHTDG=='T13' ||
            $dshtdg->maHTDG=='T14' || $dshtdg->maHTDG=='T15' || $dshtdg->maHTDG=='T16' || $dshtdg->maHTDG=='T17'
            || $dshtdg->maHTDG=='T18' || $dshtdg->maHTDG=='T19')
            {
                //return $dshtdg->maLoaiDG;
                if ($dshtdg->maLoaiDG==4)//rubric kết thúc môn
                {
                    $maPhieuCham1=$kqdsphieucham[0]->maPhieuCham;
                    $maPhieuCham2=$kqdsphieucham[1]->maPhieuCham;
                    $trongSo=$kqdsphieucham[0]->trongSo;

                    $sql1="SELECT tieu_chi_rubric.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, sum(tieu_chi_rubric.trongSo) as 'Trongso', sum(danh_gia_rubric.diem * tieu_chi_rubric.trongSo) as 'weighted_diem'
                    FROM cdr_ctdt, danh_gia_rubric, phieu_cham, tieu_chi_rubric, muc_do_cl
                    where danh_gia_rubric.maPhieuCham= phieu_cham.maPhieuCham 
                    and cdr_ctdt.maCDR_CTDT=tieu_chi_rubric.maCDR_CTDT
                    and danh_gia_rubric.maTieuChiRubric= tieu_chi_rubric.maTieuChiRubric
                    and danh_gia_rubric.maMucDoCL=muc_do_cl.maMucDoCL
                    AND phieu_cham.maPhieuCham=".$maPhieuCham1." GROUP by tieu_chi_rubric.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT ORDER by cdr_ctdt.maCDR_CTDT_VB";
                    
                    $ketquathongke1= db::select($sql1);
                    
                    $sql2="SELECT tieu_chi_rubric.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, sum(tieu_chi_rubric.trongSo) as 'Trongso', sum(danh_gia_rubric.diem * tieu_chi_rubric.trongSo) as 'weighted_diem'
                    FROM cdr_ctdt, danh_gia_rubric, phieu_cham, tieu_chi_rubric, muc_do_cl
                    where danh_gia_rubric.maPhieuCham= phieu_cham.maPhieuCham 
                    and cdr_ctdt.maCDR_CTDT=tieu_chi_rubric.maCDR_CTDT
                    and danh_gia_rubric.maTieuChiRubric= tieu_chi_rubric.maTieuChiRubric
                    and danh_gia_rubric.maMucDoCL=muc_do_cl.maMucDoCL
                    AND phieu_cham.maPhieuCham=".$maPhieuCham2." GROUP by tieu_chi_rubric.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT ORDER by cdr_ctdt.maCDR_CTDT_VB";
                   
                    $ketquathongke2= db::select($sql2);
                    $arrR2 = [];
                    foreach ($ketquathongke2 as $item) {
                        $arrR2[$item->maCDR_CTDT] = $item;
                    }

                    foreach($ketquathongke1 as $kq)
                    {
                        if ($kq->Trongso == 0) continue;
                        $mang2chieu[$sodong][0]= $kq->maCDR_CTDT;
                        $mang2chieu[$sodong][1]= $kq->maCDR_CTDT_VB;
                        $mang2chieu[$sodong][2]= $kq->tenCDR_CTDT;  
                                  
                        $kq1= $kq->weighted_diem/$kq->Trongso;
                        $kq2=(isset($arrR2[$kq->maCDR_CTDT]) && $arrR2[$kq->maCDR_CTDT]->Trongso > 0) ? $arrR2[$kq->maCDR_CTDT]->weighted_diem/$arrR2[$kq->maCDR_CTDT]->Trongso : 0;                                 
                          
                        $kq_diem=($kq1+$kq2)/2;                                  
      
                        $mang2chieu[$sodong][3]=$kq_diem;
                        $mang2chieu[$sodong][4]=$trongSo;
                        $mang2chieu[$sodong][5]=$dshtdg->maLoaiDG;
                        $sodong++;   
                    }
                }           
                else//rubric quá trình
                {
                    $maPhieuCham=$kqdsphieucham[0]->maPhieuCham;
                    $trongSo=$kqdsphieucham[0]->trongSo;
 
                    $sql="SELECT tieu_chi_rubric.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT, sum(tieu_chi_rubric.trongSo) as 'Trongso', sum(danh_gia_rubric.diem * tieu_chi_rubric.trongSo) as 'weighted_diem'
                    FROM cdr_ctdt, danh_gia_rubric, phieu_cham, tieu_chi_rubric, muc_do_cl
                    where danh_gia_rubric.maPhieuCham= phieu_cham.maPhieuCham 
                    and cdr_ctdt.maCDR_CTDT=tieu_chi_rubric.maCDR_CTDT
                    and danh_gia_rubric.maTieuChiRubric= tieu_chi_rubric.maTieuChiRubric
                    and danh_gia_rubric.maMucDoCL=muc_do_cl.maMucDoCL
                    AND phieu_cham.maPhieuCham=".$maPhieuCham." GROUP by tieu_chi_rubric.maCDR_CTDT, cdr_ctdt.maCDR_CTDT_VB, cdr_ctdt.tenCDR_CTDT ORDER by cdr_ctdt.maCDR_CTDT_VB";
                  
                    $ketquathongke= db::select($sql);
 
                    foreach($ketquathongke as $kq)
                    {
                        if ($kq->Trongso == 0) continue;
                        $mang2chieu[$sodong][0]= $kq->maCDR_CTDT;
                        $mang2chieu[$sodong][1]= $kq->maCDR_CTDT_VB;
                        $mang2chieu[$sodong][2]= $kq->tenCDR_CTDT;                                 
 
                        $kq_diem= $kq->weighted_diem/$kq->Trongso;
 
                        $mang2chieu[$sodong][3]=$kq_diem;
                        $mang2chieu[$sodong][4]=$trongSo;
                        $mang2chieu[$sodong][5]=$dshtdg->maLoaiDG;
                        $sodong++;    
                    }
                }
            }    
        }
    }
        $Sinh_vien=db::select("SELECT * FROM `sinh_vien` WHERE  sinh_vien.maSSV='".$maSSV."'");
      
       
        $hocPhan= hocPhan::where('maHocPhan',$maHocPhan)->where('isDelete',false)->orderbyDesc('maHocPhan')->first(['maHocPhan','maHocPhan_VB','tenHocPhan']);

        if ($sodong == 0 || (count($mang2chieu) <= 1 && empty($mang2chieu[0]))) {
            return view('covan.cdr_ctdt', [
                "hocPhan"    => $hocPhan,
                "Sinh_vien"  => $Sinh_vien,
                "mang2chieu" => [],
                "maHKNH"     => Session::get('maHKNH'),
                "message"    => "Chưa có dữ liệu đánh giá CĐR CTĐT cho học phần này!"
            ]);
        }

       return view('covan.cdr_ctdt', [
            "hocPhan"    => $hocPhan,
            "Sinh_vien"  => $Sinh_vien,
            "mang2chieu" => $mang2chieu,
            "maHKNH"     => Session::get('maHKNH'),
        ]);
   
}

    public function monhoc_ctdt($maSSV, $maHocPhan, $maLop)
    {
        
        $nhomLop = DB::table('nhom_lop')
            ->join('sinh_vien_nhom_lop', 'nhom_lop.maNhomLop', '=', 'sinh_vien_nhom_lop.maNhomLop')
            ->where('nhom_lop.maHocPhan', $maHocPhan)
            ->where('nhom_lop.maLop', $maLop)
            ->where('sinh_vien_nhom_lop.maSSV', $maSSV)
            ->where('nhom_lop.isDelete', false)
            ->where('sinh_vien_nhom_lop.isDelete', false)
            ->first(['nhom_lop.maNhomLop']);

        $maNhomLop = $nhomLop ? $nhomLop->maNhomLop : $maLop;

        Session::put('maHocPhan', $maHocPhan);
        Session::put('maNhomLop', $maNhomLop);

        return $this->thong_ke_chi_tiet_KQHT_SinhVien_PI($maSSV);
    }
}

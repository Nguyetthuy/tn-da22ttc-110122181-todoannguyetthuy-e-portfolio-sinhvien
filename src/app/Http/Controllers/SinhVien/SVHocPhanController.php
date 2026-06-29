<?php

namespace App\Http\Controllers\sinhvien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\sinhVien;
use App\Models\hocPhan;
use App\Models\giangVien;
use App\Models\lopHanhChinh;
use App\Models\giangDay;
use App\Models\phieu_khao_sat;
use Illuminate\Support\Facades\Session;

class SVHocPhanController extends Controller
{
    public function index()
    {
        $sv = sinhVien::where('sinh_vien.isDelete', false)
            ->where('maSSV', Session::get('maSSV'))
            ->join('lop_hanh_chinh', function ($a) {
                $a->on('lop_hanh_chinh.maLop', '=', 'sinh_vien.maLop')
                  ->where('lop_hanh_chinh.isDelete', false);
            })
            ->join('giangday', function ($q) {
                $q->on('giangday.maLop', '=', 'lop_hanh_chinh.maLop')
                  ->where('giangday.isDelete', false);
            })
            ->join('hoc_phan', function ($x) {
                $x->on('hoc_phan.maHocPhan', '=', 'giangday.maHocPhan')
                  ->where('hoc_phan.isDelete', false);
            })
            ->get([
                'hoc_phan.maHocPhan',
                'giangday.maHKNH',
                'hoc_phan.tenHocPhan',
                'giangday.maLop',
                'sinh_vien.maSSV'
            ]);

        $hp = hocPhan::where('isDelete', false)->get();
        $giangvien = giangVien::where('isDelete', false)->get();
        $lop = lopHanhChinh::where('isDelete', false)->get();

        $gd_data = giangDay::where('isDelete', false)->get();

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

        return view('sinhvien.hocphan.hocphan', [
            'giangday' => $sv,
            'hocphan' => $hp,
            'giangvien' => $giangvien,
            'lop' => $lop
        ]);
    }

    public function monhoc($maHocPhan)
    {
        $giangday = giangDay::where('giangday.maHocPhan', $maHocPhan)
            ->where('giangday.isDelete', false)
            ->join('hoc_phan', function ($c) {
                $c->on('hoc_phan.maHocPhan', '=', 'giangday.maHocPhan')
                  ->where('hoc_phan.isDelete', false);
            })
            ->join('giang_vien', function ($c) {
                $c->on('giang_vien.maGV', '=', 'giangday.maGV')
                  ->where('giang_vien.isDelete', false);
            })
            ->join('lop_hanh_chinh', function ($c) {
                $c->on('lop_hanh_chinh.maLop', '=', 'giangday.maLop')
                  ->where('lop_hanh_chinh.isDelete', false);
            })
            ->join('sinh_vien', function ($b) {
                $b->on('sinh_vien.maLop', '=', 'lop_hanh_chinh.maLop')
                  ->where('sinh_vien.maSSV', Session::get('maSSV'))
                  ->where('sinh_vien.isDelete', false);
            })
            ->get();

        $hp = hocPhan::where('isDelete', false)->get();
        $giangvien = giangVien::where('isDelete', false)->get();
        $lop = lopHanhChinh::where('isDelete', false)->get();

    }
}
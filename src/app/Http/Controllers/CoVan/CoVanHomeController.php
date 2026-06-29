<?php

namespace App\Http\Controllers\CoVan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\coVanLop;

class CoVanHomeController extends Controller
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
                DB::raw('(SELECT COUNT(*) FROM sinh_vien WHERE sinh_vien.maLop = lop_hanh_chinh.maLop) as soSinhVien')
            )
            ->get();

        return view('covan.home', compact('dsLop'));
    }
}
<?php

namespace App\Http\Controllers;

use Session;
use App\Models\users;
use App\Models\cauHoi;
use App\Models\hocPhan;
use App\Models\cdr3_abet;
use App\Models\giangVien;
use App\Models\sinhVien; // them model sinh viên
use Illuminate\Http\Request;
use App\Models\hocPhan_kqHTHP;
use App\Models\phuongAnTuLuan;
use App\Models\tieuChiChamDiem;
use App\Models\phuongAnTracNghiem;
use App\Models\boMon;
use App\Http\Controllers\CommonController;

class loginController extends Controller
{
    public function index()
    {
        //    $hocPhan=hocPhan::pluck('maHocPhan');
        //    $hp_kqht=tieuChiChamDiem::all();
        //    foreach ($hp_kqht as $key => $value) {
        //        $cdr3_abet=cdr3_abet::where('maCDR3',$value->maCDR3)->first();
        //        if($cdr3_abet){
        //             $value->maChuanAbet=$cdr3_abet->maChuanAbet;
        //             $value->update();
        //        }
        //    }
        // foreach ($hp_kqht as $key => $value) {
        //     if(hocPhan_kqHTHP::where('maHocPhan',$value->maHocPhan)
        //     ->where('maKQHT',$value->maKQHT)->where('maCDR3',$value->maCDR3)
        //     ->where('maChuanAbet',$value->maChuanAbet)
        //     ->count(['maHocPhan'])>1)
        //     {
        //          $value->delete();
        //     }
        // }
        //return $hp_kqht;

        //duyet qua bang
        // $pa_tn=phuongAnTuLuan::all();
        // foreach ($pa_tn as $pa) {
        //     if($pa->maCDR3!=null){
        //         $cdr3_abet=cdr3_abet::where('maCDR3',$pa->maCDR3)->first();
        //         if($cdr3_abet){
        //             $pa->maChuanAbet=$cdr3_abet->maChuanAbet;
        //             $pa->update();
        //         }
        //     }
        // }
        

        Session::put('language','vi');
        if(Session::get('user_permission') == 1)	
            return redirect('/quan-ly');
        if(Session::get('user_permission') == 2)
            return redirect('/giang-vien');
        if(Session::get('user_permission') == 3){
            return redirect('/giao-vu');
        }
        if(Session::get('user_permission') == 4){
        //    return 123;
            return redirect('/bo-mon');
        }
        if(Session::get('user_permission')== 5){
            return redirect('/sinh-vien');
        }
        if(Session::get('user_permission')== 6){
            return redirect('/covan');
        }
        return view('login');
    }

    public function login_submit(Request $request)
    {
      
        $Users=users::where('username',$request->username)->where('password',md5($request->password))
        ->first();
        
        if($Users){
            if ($Users->isBlock) {
                return back()->with('warning','T&#1043;�i kho&#1073;&#1108;&#1032;n &#1044;�&#1043;&#1032; b&#1073;�� kh&#1043;&#1110;a!!!');
            }
            Session::put('user_permission',$Users->permission);
            Session::put('user_name',$Users->username);
            if($Users->permission == 1)	 //quan tri
                return redirect('/quan-ly'); 
            if($Users->permission == 2){  //giang vien
                $gv=giangVien::where('username',$request->username)->first();

                Session::put('maGV',$gv->maGV);
                Session::put('hoGV',$gv->hoGV);
                Session::put('tenGV',$gv->tenGV);
                return redirect('/giang-vien');
            }    
            if($Users->permission== 3){   //giao vu
                return redirect('/giao-vu');
            }
            if($Users->permission==4){//bo mon
                $gv=giangVien::where('username',$request->username)->first();
                Session::put('username',$request->username);
           //   return $gv->maBM;
                $bm=boMon::where('maBM',$gv->maBM)->first();

                Session::put('maBM',$gv->maBM);
                Session::put('tenBM',$bm->tenBM);
                return redirect('/bo-mon');
            }
            if($Users->permission ==5){//sinh viên
                $sv = sinhVien::where('maSSV', $request->username)->first();
                Session::put('maSSV',$sv->maSSV );
                Session::put('tenSV', $sv->HoSV . ' ' . $sv->TenSV);
                Session::put('maLop', $sv->maLop);
                return redirect('/sinh-vien');
            }
            if($Users->permission ==6){//cố vấn
                $sv = giangVien::where('username', $request->username)->first();
                Session::put('maGV',$sv->maGV );
                Session::put('hoGV', $sv->hoGV);
                Session::put('tenGV', $sv->TenGV);
                return redirect('/covan');
            }

           
       }
       return back()->with('warning','&#1044;&#1106;&#1044;&#1107;ng nh&#1073;&#1108;�p kh&#1043;&#1169;ng th&#1043;�nh c&#1043;&#1169;ng!!!');

    }

    public function logout()
    {
        $lang=Session::get('language');
        Session::flush();
        Session::put('language', $lang);
        return redirect('/dang-nhap');
    }
}

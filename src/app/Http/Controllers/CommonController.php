<?php

namespace App\Http\Controllers;

use session;
use App\Models\cdr3_abet;
use Illuminate\Http\Request;

class CommonController extends Controller
{

    public static function success_notify($vi_text,$en_text)
    {
        if (session::has('language') && session::get('language')=='vi') {
            alert()->success($vi_text,'Thông báo');
        } else {
            alert()->success($en_text,'Message');
        }
    }

    public static function warning_notify($vi_text,$en_text)
    {
        if (session::has('language') && session::get('language')=='vi') {
             alert()->warning($vi_text,'Thông báo');
        } else {
             alert()->warning($en_text,'Message');
        }
    }


    //ham chuyen chuoi co dau thanh chuoi khong dau
    public static function con_str($in_str){
        $str=$in_str;  
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        $str = preg_replace("/ /", "-", $str);
        return $str;
    }

    //ham tinh diem chu
    public static function tinh_diem_chu($diem)
    {
        $diemChu="";
        if($diem>=0 && $diem<4){
            $diemChu="F";
        }
        
        if($diem>=4 && $diem<=4.9){
             $diemChu="D";
        }
        if($diem>=5 && $diem<=5.4){
            $diemChu="D+";
        }

        if($diem>=5.5 && $diem<=6.4){
            $diemChu="C";
        }
        if($diem>=6.5 && $diem<=6.9){
            $diemChu="C+";
        }

        if($diem>=7 && $diem<=7.9){
            $diemChu="B";
        }
        if($diem>=8.0 && $diem<=8.9){
            $diemChu="B+";
        }
        
        if($diem>=9.0 && $diem<=10){
            $diemChu="A";
        }
        return $diemChu;
    }

    //ham tinh diem so
    public static function tinh_xep_hang($diem)
    {
        $xepHang="";
        if($diem>=0 && $diem<4){ //kem
            $xepHang=5;
        }
        
        if($diem>=4 && $diem<=4.9){ //yeu
             $xepHang=4;
        }
        if($diem>=5 && $diem<=5.4){ //yeu
            $xepHang=4;
        }

        if($diem>=5.5 && $diem<=6.4){ //trung binh
            $xepHang=3;
        }
        if($diem>=6.5 && $diem<=6.9){ //trung binh
            $xepHang=3;
        }

        if($diem>=7 && $diem<=7.9){ //kha
            $xepHang=2;
        }
        if($diem>=8.0 && $diem<=8.9){//kha
            $xepHang=2;
        }
        
        if($diem>=9.0 && $diem<=10){ //gioi
            $xepHang=1;
        }

        return $xepHang;
    }

    public static function get_abet_from_cdr3($maCDR3){ 
        echo($maCDR3);
        $cdr3_abet=cdr3_abet::where('maCDR3',$maCDR3)->first();
        return ($cdr3_abet)?$cdr3_abet->maChuanAbet:'';
    }

    public function tinh_ti_le_ket_qua_hocphan($tile_quatrinh1,$tile_quatrinh2,$tile_canbo1,$tile_canbo2)
    {
        $result=0;
        if($tile_quatrinh1!=0 && $tile_quatrinh2!=0){ //neu ti le hai lan qua trinh da co ket qua
            //tinh trung binh qua trinh
            $tile_trungbinh_quatrinh=($tile_quatrinh1+$tile_quatrinh2)/2;
            if($tile_canbo1!=0){
                $tile_trungbinh_ketthuc=0;
                if($tile_canbo2!=0){
                    $tile_trungbinh_ketthuc=($tile_canbo1+$tile_canbo2)/2;
                }else{
                    $tile_trungbinh_ketthuc=$tile_canbo1;
                }
                $result=($tile_trungbinh_quatrinh+$tile_trungbinh_ketthuc)/2;
                return $result;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
        return $result;
    }
}

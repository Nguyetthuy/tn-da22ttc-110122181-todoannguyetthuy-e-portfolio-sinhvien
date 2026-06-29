<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTyLeDgHockyToThongkePloSinhvienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('thongke_plo_sinhvien', function (Blueprint $table) {
        // Thêm cột kiểu FLOAT hoặc DECIMAL để lưu số phần trăm (Ví dụ: 25.50%)
        $table->decimal('ty_le_dg_hocky', 5, 2)->default(0)->after('ty_le_dong_gop');
    });
}

public function down()
{
    Schema::table('thongke_plo_sinhvien', function (Blueprint $table) {
        $table->dropColumn('ty_le_dg_hocky');
    });
}
}

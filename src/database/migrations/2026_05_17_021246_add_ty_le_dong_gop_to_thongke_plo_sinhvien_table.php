<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTyLeDongGopToThongkePloSinhvienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thongke_plo_sinhvien', function (Blueprint $table) {
            $table->float('ty_le_dong_gop')->nullable()->default(0)->comment('Ty le dong gop cua mon hoc vao PLO nay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thongke_plo_sinhvien', function (Blueprint $table) {
            $table->dropColumn('ty_le_dong_gop');
        });
    }
}

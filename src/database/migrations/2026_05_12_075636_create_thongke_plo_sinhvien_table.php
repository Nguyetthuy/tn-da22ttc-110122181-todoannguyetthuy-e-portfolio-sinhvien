<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThongkePloSinhvienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('thongke_plo_sinhvien', function (Blueprint $table) {
                $table->id();
                $table->string('maSSV');
                $table->string('maHocPhan');
                $table->string('maHKNH');
                $table->string('maCDR_CTDT');
        $table->float('ty_le_dat')->default(0); // Tỷ lệ đạt (%) của PLO trong môn học
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thongke_plo_sinhvien');
    }
}

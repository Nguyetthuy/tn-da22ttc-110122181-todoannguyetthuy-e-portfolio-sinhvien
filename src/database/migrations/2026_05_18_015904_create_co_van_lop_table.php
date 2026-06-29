<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoVanLopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_van_lop', function (Blueprint $table) {
            $table->id();
            $table->string('maGV');
            $table->string('maLop');
            $table->date('ngayBatDau');
            $table->date('ngayKetThuc')->nullable();
            $table->boolean('isDelete')->default(false);
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('maGV')->references('maGV')->on('giang_vien')->onDelete('cascade');
            $table->foreign('maLop')->references('maLop')->on('lop_hanh_chinh')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_van_lop');
    }
}

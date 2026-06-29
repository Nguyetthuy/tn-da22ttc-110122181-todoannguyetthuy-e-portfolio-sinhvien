<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create26LopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lop_hanh_chinh', function (Blueprint $table) {
          //  $table->string('maLop',191)->primary('maLop');
          $table->string('maLop',191)->unique();
          $table->primary('maLop');
            $table->text('tenLop')->nullable()->default(null);
            $table->integer('maKhoaTuyenSinh')->unsigned()->nullable()->default(12);
            $table->integer('maCT')->unsigned()->nullable()->default(12);
            $table->boolean('isDelete')->nullable()->default(false);
            $table->timestamps();
            $table->foreign('maCT')->references('maCT')->on('ct_dao_tao')->onDelete('cascade');
            $table->foreign('maKhoaTuyenSinh')->references('maKhoaTuyenSinh')->on('khoa_tuyensinh')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lop_hanh_chinh');
    }
}

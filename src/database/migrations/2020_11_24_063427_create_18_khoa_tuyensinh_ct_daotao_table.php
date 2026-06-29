<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create18KhoaTuyensinhCtDaotaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khoa_tuyensinh_ct_daotao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('maKhoaTuyenSinh')->unsigned()->nullable()->default(12);
            $table->integer('maCT')->unsigned()->nullable()->default(12);
            $table->boolean('isDelete')->nullable()->default(false);
            $table->integer('maCDR_CTDT')->unsigned()->nullable()->default(1);
            $table->timestamps();
            $table->foreign('maKhoaTuyenSinh')->references('maKhoaTuyenSinh')->on('khoa_tuyensinh')->onDelete('cascade');
            $table->foreign('maCT')->references('maCT')->on('ct_dao_tao')->onDelete('cascade');
            $table->foreign('maCDR_CTDT')->references('maCDR_CTDT')->on('CDR_CTDT')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khoa_tuyensinh_ct_daotao');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create28GiangDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GIANGDAY', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('maHocPhan')->unsigned()->nullable()->default(1);

           $table->string('maLop',191);

            $table->string('maGV',20);
            $table->string('maHKNH',20);

            $table->boolean('isDelete')->nullable()->default(false);
            $table->foreign('maHKNH')->references('maHKNH')->on('hocky_namhoc')->onDelete('cascade');
            $table->foreign('maLop')->references('maLop')->on('lop_hanh_chinh')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('maGV')->references('maGV')->on('GIANG_VIEN')
                ->onUpdate('restrict')
                ->onDelete('cascade');    
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
        Schema::dropIfExists('GIANGDAY');
    }
}

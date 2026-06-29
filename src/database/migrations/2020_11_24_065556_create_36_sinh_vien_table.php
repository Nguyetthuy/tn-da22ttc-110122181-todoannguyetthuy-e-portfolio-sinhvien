<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create36SinhVienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SINH_VIEN', function (Blueprint $table) {
            $table->string('maSSV',20)->unique();
            $table->primary('maSSV');
            $table->text('HoSV')->nullable()->default(null);
            $table->text('TenSV')->nullable()->default(null);
            $table->text('Phai')->nullable()->default(null);
            $table->text('NgaySinh')->nullable()->default(null);
            $table->string('maLop',191);
            $table->string('username',191);
            $table->foreign('maLop')->references('maLop')->on('lop_hanh_chinh')->onDelete('cascade');

            $table->boolean('isDelete')->nullable()->default(false);
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
        Schema::dropIfExists('SINH_VIEN');
    }
}

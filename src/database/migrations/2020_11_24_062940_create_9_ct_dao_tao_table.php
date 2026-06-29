<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create9CtDaoTaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CT_DAO_TAO', function (Blueprint $table) {
            $table->increments('maCT');
            $table->text('tenCT')->nullable()->default(null);
          
            $table->string('maBac',191);
            $table->integer('maCNganh')->unsigned()->nullable()->default(1);
            $table->string('maHe',191);
            $table->string('maBM',191);
            $table->text('soQuyetDinh')->nullable()->default(null);
            $table->text('ngayBanHanh')->nullable()->default(null);
            
            $table->boolean('isDelete')->nullable()->default(false);
            //them ma bo mon
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
        Schema::dropIfExists('CT_DAO_TAO');
    }
}

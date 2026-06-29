<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create15CdrCtdtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CDR_CTDT', function (Blueprint $table) {
            $table->increments('maCDR_CTDT');
            $table->text('maCDR_CTDT_VB')->nullable()->default(null);
            $table->text('tenCDR_CTDT')->nullable()->default(null);
            $table->integer('maCT')->unsigned()->nullable()->default(1);
            $table->boolean('isDelete')->nullable()->default(false);
            $table->timestamps();

            $table->foreign('maCT')->references('maCT')->on('CT_DAO_TAO')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CDR_CTDT');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create25GiangVienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GIANG_VIEN', function (Blueprint $table) {
            $table->string('maGV',191)->unique();
            $table->primary('maGV');
            $table->text('hoGV')->nullable()->default(null);
            $table->text('tenGV')->nullable()->default(null);
            $table->string('username');
            $table->text('email')->nullable()->default(null);
            $table->boolean('isDelete')->nullable()->default(false);
            $table->string('maBM',191);
            $table->timestamps();
            $table->foreign('username')->references('username')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GIANG_VIEN');
    }
}

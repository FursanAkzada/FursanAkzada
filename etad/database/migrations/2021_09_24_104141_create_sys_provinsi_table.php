<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysProvinsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('kode')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('sys_kota', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('provinsi_id')->nullable();
            $table->string('nama')->nullable();
            $table->string('kode')->nullable();

            $table->foreign('provinsi_id')->references('id')->on('sys_provinsi');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('sys_kota');
        Schema::dropIfExists('sys_provinsi');
    }
}

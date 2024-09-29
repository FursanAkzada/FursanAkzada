<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDerajatKeduaTipeKeluarga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'derajat_kedua_tipe_keluarga',
            function (Blueprint $table) {
                $table->integer('id_tipe')->nullable();
                $table->string('nama_tipe', 50)->nullable();
                $table->string('keterangan', 50)->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('derajat_kedua_tipe_keluarga');
    }
}

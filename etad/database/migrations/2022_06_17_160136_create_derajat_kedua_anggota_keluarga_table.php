<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDerajatKeduaAnggotaKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'derajat_kedua_anggota_keluarga',
            function (Blueprint $table) {
                $table->integer('id_anggota')->nullable();
                $table->string('nip')->nullable();
                $table->string('nama_anggota')->nullable();
                $table->string('nip_anggota')->nullable();
                $table->string('id_status')->nullable();
                $table->string('keterangan')->nullable();
                $table->string('status')->nullable();
                $table->string('id_tipe')->nullable();
                $table->string('pernyataan')->nullable();
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
        Schema::dropIfExists('derajat_kedua_anggota_keluarga');
    }
}

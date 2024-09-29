<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanMutasiPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengajuan_mutasi_pegawai',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tad_id');
                $table->unsignedBigInteger('kepegawaian_id');
                $table->date('tgl_mutasi')->nullable();
                $table->date('tgl_efektif')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('trans_pengajuan_mutasi_pegawai');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanMutasiPegawaiAddPengajuanColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_mutasi_pegawai',
            function (Blueprint $table) {
                $table->unsignedBigInteger('pengajuan_id')->after('id');

                $table->foreign('pengajuan_id')
                    ->references('id')
                    ->on('trans_pengajuan_mutasi')
                    ->onDelete('cascade');
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
        Schema::table(
            'trans_pengajuan_mutasi_pegawai',
            function (Blueprint $table) {
                $table->dropForeign(['pengajuan_id']);
                $table->dropColumn(['pengajuan_id']);
            }
        );
    }
}
